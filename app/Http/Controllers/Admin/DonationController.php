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
            'purpose' => 'required|in:tithes,offering,mission',
            'payment_method' => 'required|in:cash,check',
            'notes' => 'nullable|string',
        ]);

        // Set transaction date to current time
        $validated['transaction_date'] = now();
        $validated['admin_id'] = auth()->id();
        
        // For manual donations, automatically set status to verified
        $validated['status'] = 'verified';
        $validated['verified_by'] = auth()->user()->name;
        $validated['verification_date'] = now();

        // If payment method is check, validate check details
        if ($request->payment_method === 'check') {
            $checkValidation = $request->validate([
                'check_number' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'check_date' => 'required|date',
            ]);
            
            $validated = array_merge($validated, $checkValidation);
        }

        Donation::create($validated);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Manual donation added successfully.');
    }
} 


