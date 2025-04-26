<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function manualCreate()
    {
        return view('admin.donations.manual-create');
    }

    public function manualStore(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'screenshot' => 'nullable|image|max:2048',
            'payment_method' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'status' => 'required|string|max:255',
            'verification_notes' => 'nullable|string',
        ]);

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('donation-screenshots', 'public');
            $validated['screenshot'] = $path;
        }

        $validated['admin_id'] = auth()->id();
        $validated['donor_name'] = $request->donor_name;

        Donation::create($validated);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Manual donation added successfully.');
    }
} 