<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use App\Notifications\NewDonationNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        $rules = [
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:gcash',
            'purpose' => 'required|string',
            'notes' => 'nullable|string',
            'screenshot' => 'required|image|max:2048',
            'reference_number' => 'required|string',
        ];
        
        $validated = $request->validate($rules);

        $donation = new \App\Models\Donation();
        $donation->user_id = auth()->id();
        $donation->amount = $validated['amount'];
        $donation->payment_method = $validated['payment_method'];
        $donation->purpose = $validated['purpose'];
        $donation->reference_number = $validated['reference_number'];
        
        // Set transaction_date to current time
        $donation->transaction_date = now();
        
        $donation->notes = $validated['notes'] ?? null;
        $donation->status = 'pending';
        
        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('donations', 'public');
            $donation->screenshot = $path;
        }
        
        $donation->save();

        // Find all treasurers
        $treasurers = \App\Models\User::where('role', 2)->get();

        // Log how many treasurers were found
        \Illuminate\Support\Facades\Log::info('Found ' . $treasurers->count() . ' treasurers to notify about donation #' . $donation->id);

        // If we found any treasurers, notify them
        if ($treasurers->isNotEmpty()) {
            foreach ($treasurers as $treasurer) {
                try {
                    // Log before sending notification
                    \Illuminate\Support\Facades\Log::info('Attempting to notify treasurer: ' . $treasurer->id . ' - ' . $treasurer->name);
                    
                    // Send notification immediately
                    $treasurer->notifyNow(new \App\Notifications\NewDonationNotification($donation));
                    
                    // Log success
                    \Illuminate\Support\Facades\Log::info('Successfully sent notification to treasurer: ' . $treasurer->id);
                } catch (\Exception $e) {
                    // Log the error but continue execution
                    \Illuminate\Support\Facades\Log::error('Failed to send notification to treasurer ' . $treasurer->id . ': ' . $e->getMessage());
                }
            }
        } else {
            // If no treasurers found, log this issue
            \Illuminate\Support\Facades\Log::warning('No treasurers found to notify about donation #' . $donation->id);
        }

        return redirect()->route('member.donations.index')
            ->with('success', 'Donation submitted successfully and is pending approval.');
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

























