<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('DonationController@index called');
        $donations = Donation::with(['user', 'admin'])
            ->latest()
            ->paginate(10);
            
        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.donations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:gcash',
            'transaction_date' => 'required|date',
        ]);

        $donation = Donation::create($validated);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        return view('admin.donations.edit', compact('donation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,declined',
            'admin_notes' => 'nullable|string',
        ]);

        $donation->update($validated);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation deleted successfully.');
    }

    /**
     * Show the form for creating a manual donation.
     */
    public function manualCreate()
    {
        $users = User::where('role', 'member')
            ->orWhereNull('role')
            ->get();
        return view('admin.donations.manual-create', compact('users'));
    }
}

