<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations.
     */
    public function index()
    {
        $donations = Donation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('member.donations', compact('donations'));
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donation_type' => 'required|in:tithe,offering,mission',
            'amount' => 'required|numeric|min:1',
            'reference_number' => 'required|string|max:255',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'message' => 'nullable|string|max:1000',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('donation-receipts', 'public');
        }

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'donor_name' => $request->donor_name,
            'donation_type' => $request->donation_type,
            'amount' => $request->amount,
            'reference_number' => $request->reference_number,
            'receipt_path' => $receiptPath,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('member.donations')
            ->with('success', 'Donation submitted successfully! It will be reviewed by our team.');
    }

    /**
     * Display the specified donation receipt.
     */
    public function showReceipt($id)
    {
        $donation = Donation::findOrFail($id);
        
        // Check if the user is authorized to view this receipt
        if ($donation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$donation->receipt_path) {
            return redirect()->back()->with('error', 'No receipt available for this donation.');
        }
        
        return response()->file(storage_path('app/public/' . $donation->receipt_path));
    }
} 