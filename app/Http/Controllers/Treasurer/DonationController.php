<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use App\Notifications\DonationApprovedNotification;
use App\Notifications\DonationDeclinedNotification;
use App\Notifications\DonationStatusNotification;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Donation::with('user')->orderBy('created_at', 'desc');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Month filter (YYYY-MM)
        if ($request->filled('date')) {
            try {
                $start = \Carbon\Carbon::createFromFormat('Y-m', $request->input('date'))->startOfMonth();
                $end = (clone $start)->endOfMonth();
                $query->whereBetween('created_at', [$start, $end]);
            } catch (\Exception $e) {
                // Ignore invalid date format; fallback to no date filtering
            }
        }

        $donations = $query->paginate(10);
        $donations->appends($request->query());
        
        return view('treasurer.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new donation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('treasurer.donations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|in:tithes,offering,mission',
            'payment_method' => 'required|in:cash,check',
            'notes' => 'nullable|string',
            'anonymous' => 'nullable|boolean',
        ]);

        // If payment method is check, validate check details
        if ($request->payment_method === 'check') {
            $checkValidation = $request->validate([
                'check_number' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'check_date' => 'required|date',
            ]);
            $data = array_merge($data, $checkValidation);
        }

        // Set donor_name automatically
        $data['anonymous'] = $request->has('anonymous') ? true : false;
        if ($data['anonymous']) {
            $data['donor_name'] = null; // or 'Anonymous' if you prefer
        } else {
            $data['donor_name'] = auth()->user()->getFullName();
        }

        // Set transaction_date to current time
        $data['transaction_date'] = now();

        // For manual donations, automatically set status to verified
        $data['status'] = 'verified';
        $data['verified_by'] = auth()->user()->name;
        $data['verification_date'] = now();

        Donation::create($data);

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation added successfully.');
    }

    /**
     * Display the specified donation.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show(Donation $donation)
    {
        return view('treasurer.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donation $donation)
    {
        return view('treasurer.donations.edit', compact('donation'));
    }

    /**
     * Update the specified donation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,declined',
            'verification_notes' => 'required|string',
        ]);

        $donation->update([
            'status' => $validated['status'],
            'verified_by' => auth()->user()->name,
            'verification_date' => now(),
            'verification_notes' => $validated['verification_notes']
        ]);

        // Send appropriate notification based on status
        if ($donation->user) {
            if ($validated['status'] === 'verified') {
                $donation->user->notify(new DonationApprovedNotification($donation));
            } else {
                $donation->user->notify(new DonationDeclinedNotification($donation));
            }
        }

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation ' . ($validated['status'] === 'verified' ? 'approved' : 'declined') . ' successfully.');
    }

    /**
     * Remove the specified donation from storage.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donation $donation)
    {
        // Delete receipt image if exists
        if ($donation->receipt_image) {
            Storage::disk('public')->delete($donation->receipt_image);
        }
        
        $donation->delete();

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation deleted successfully.');
    }

    /**
     * Approve a donation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'verification_notes' => 'nullable|string',
        ]);

        $donation->update([
            'status' => 'verified',
            'verified_by' => auth()->user()->name,
            'verification_date' => now(),
            'verification_notes' => $validated['verification_notes'] ?? null
        ]);

        // Notify donor about approved donation if they have a user account
        if ($donation->user) {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to notify user: ' . $donation->user->id . ' about approved donation');
                $donation->user->notifyNow(new \App\Notifications\DonationStatusNotification($donation));
                \Illuminate\Support\Facades\Log::info('Successfully sent notification to user: ' . $donation->user->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send notification to user ' . $donation->user->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation approved successfully.');
    }

    /**
     * Decline a donation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function decline(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'treasurer_response' => 'required|string',
        ]);

        $donation->update([
            'status' => 'declined',
            'verified_by' => auth()->user()->name,
            'verification_date' => now(),
            'treasurer_response' => $validated['treasurer_response']
        ]);

        // Notify donor about declined donation if they have a user account
        if ($donation->user) {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to notify user: ' . $donation->user->id . ' about declined donation');
                $donation->user->notifyNow(new \App\Notifications\DonationStatusNotification($donation));
                \Illuminate\Support\Facades\Log::info('Successfully sent notification to user: ' . $donation->user->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send notification to user ' . $donation->user->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation declined successfully.');
    }

    /**
     * Verify a donation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'verification_notes' => 'required|string',
            'status' => 'required|in:verified,declined'
        ]);

        $donation->update([
            'status' => $validated['status'],
            'verified_by' => auth()->user()->name,
            'verification_date' => now(),
            'verification_notes' => $validated['verification_notes']
        ]);

        // Notify donor about verification
        if ($donation->user) {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to notify user: ' . $donation->user->id . ' about donation verification');
                $donation->user->notify(new DonationStatusNotification($donation));
                \Illuminate\Support\Facades\Log::info('Successfully sent notification to user: ' . $donation->user->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send notification to user ' . $donation->user->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('treasurer.donations.index')
            ->with('success', 'Donation verification completed successfully.');
    }
}

