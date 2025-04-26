<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use App\Notifications\NewDonationNotification;
use App\Notifications\DonationApprovedNotification;
use App\Notifications\DonationDeclinedNotification;
use App\Notifications\DonationStatusNotification;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['user', 'admin'])
            ->latest()
            ->paginate(10);
            
        return view('admin.donations.index', compact('donations'));
    }

    public function create()
    {
        return view('admin.donations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:gcash',
            'transaction_date' => 'required|date',
        ]);

        $donation = Donation::create($validated);

        // Notify admin about new donation
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewDonationNotification($donation));
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    public function manualCreate()
    {
        $users = User::where('role', 'member')
            ->orWhereNull('role')
            ->get();
        return view('admin.donations.manual-create', compact('users'));
    }

    public function manualStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,online_payment,gcash',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,verified',
            'verification_notes' => 'required_if:status,verified|nullable|string',
        ]);

        $donation = Donation::create($validated);

        if ($validated['status'] === 'verified') {
            $donation->update([
                'verified_by' => auth()->user()->name,
                'verification_date' => now(),
            ]);
            
            // Notify donor about the verified donation
            $donation->user->notify(new DonationStatusNotification($donation));
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    public function edit(Donation $donation)
    {
        return view('admin.donations.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,verified',
            'verification_notes' => 'required_if:status,verified|nullable|string',
        ]);

        $donation->update($validated);

        if ($validated['status'] === 'verified') {
            $donation->update([
                'verified_by' => auth()->user()->name,
                'verification_date' => now(),
            ]);
            
            // Notify donor about the verified donation
            $donation->user->notify(new DonationStatusNotification($donation));
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation updated successfully.');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation deleted successfully.');
    }

    public function show(Donation $donation)
    {
        $donation->load(['user', 'admin']);
        return view('admin.donations.show', compact('donation'));
    }

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
        $donation->user->notify(new DonationStatusNotification($donation));

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation verification completed successfully.');
    }

    public function showQrCode()
    {
        return view('admin.donations.qr-code');
    }

    public function approve(Donation $donation)
    {
        $donation->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'admin_response' => 'Donation approved successfully.'
        ]);

        // Notify donor about approval
        if ($donation->user) {
            $donation->user->notify(new DonationApprovedNotification($donation));
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation approved successfully.');
    }

    public function decline(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string'
        ]);

        $donation->update([
            'status' => 'declined',
            'admin_id' => auth()->id(),
            'admin_response' => $validated['admin_response']
        ]);

        // Notify donor about decline
        if ($donation->user) {
            $donation->user->notify(new DonationDeclinedNotification($donation));
        }

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation declined successfully.');
    }
}
