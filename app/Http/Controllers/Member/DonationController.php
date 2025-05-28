<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DonationController extends Controller
{
    /**
     * Display a listing of the member's donations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donations = Donation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('member.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new donation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.donations.create');
    }

    /**
     * Store a newly created donation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'purpose' => 'required|string|in:tithes,offering,mission',
            'payment_method' => 'required|string|in:gcash,bank_transfer',
            'reference_number' => 'required|numeric|digits:13',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Remove notes validation
        ]);

        try {
            // Create storage directory if it doesn't exist
            $storagePath = storage_path('app/public/donation-screenshots');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
            // Handle screenshot upload first to catch any file upload issues
            $path = null;
            if ($request->hasFile('screenshot')) {
                $path = $request->file('screenshot')->store('donation-screenshots', 'public');
            }
            
            // Create donation record
            $donation = new Donation();
            $donation->user_id = Auth::id();
            $donation->amount = $validated['amount'];
            $donation->purpose = $validated['purpose'];
            $donation->payment_method = $validated['payment_method'];
            $donation->reference_number = $validated['reference_number'];
            // Remove notes assignment
            $donation->status = 'pending';
            $donation->transaction_date = Carbon::now();
            $donation->screenshot = $path;
            
            $donation->save();
            
            return redirect()->route('member.donations.show', $donation->id)
                ->with('success', 'Your donation has been submitted and is pending approval.');
                
        } catch (\Exception $e) {
            // Log the detailed error for debugging
            \Log::error('Donation creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // In development environment, show the actual error
            if (config('app.debug')) {
                return back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
            }
            
            return back()->withInput()->withErrors(['error' => 'An error occurred while submitting your donation. Please try again.']);
        }
    }

    /**
     * Display the specified donation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $donation = Donation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('member.donations.show', compact('donation'));
    }
}








