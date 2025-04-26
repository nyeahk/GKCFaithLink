@extends('layouts.gkc')

@section('title', 'Edit Donation')

@section('content')
    <div class="edit-donation-container">
        <h1>Edit Donation</h1>
        
        <form action="{{ route('admin.donations.update', $donation->id) }}" method="POST" class="donation-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="donor_name">Donor Name</label>
                <input type="text" name="donor_name" id="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name', $donation->donor_name) }}" required>
                @error('donor_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="amount">Amount</label>
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $donation->amount) }}" step="0.01" min="0" required>
                </div>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="purpose">Purpose of Donation</label>
                <select name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" required>
                    <option value="">Select Purpose</option>
                    <option value="tithes" {{ old('purpose', $donation->purpose) == 'tithes' ? 'selected' : '' }}>Tithes</option>
                    <option value="offering" {{ old('purpose', $donation->purpose) == 'offering' ? 'selected' : '' }}>Offering</option>
                    <option value="mission" {{ old('purpose', $donation->purpose) == 'mission' ? 'selected' : '' }}>Mission</option>
                </select>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="reference_number">Reference Number (Optional)</label>
                <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number', $donation->reference_number) }}">
                @error('reference_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="screenshot">Screenshot (Optional)</label>
                @if($donation->screenshot)
                    <div class="current-screenshot mb-2">
                        <img src="{{ asset('storage/' . $donation->screenshot) }}" alt="Current Screenshot" style="max-width: 200px; border-radius: 0.375rem;">
                    </div>
                @endif
                <input type="file" name="screenshot" id="screenshot" class="form-control @error('screenshot') is-invalid @enderror" accept="image/*">
                @error('screenshot')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                    <option value="">Select Payment Method</option>
                    <option value="cash" {{ old('payment_method', $donation->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="check" {{ old('payment_method', $donation->payment_method) == 'check' ? 'selected' : '' }}>Check</option>
                    <option value="bank_transfer" {{ old('payment_method', $donation->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="online_payment" {{ old('payment_method', $donation->payment_method) == 'online_payment' ? 'selected' : '' }}>Online Payment</option>
                    <option value="gcash" {{ old('payment_method', $donation->payment_method) == 'gcash' ? 'selected' : '' }}>GCash</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $donation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $donation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ old('status', $donation->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="notes">Notes (Optional)</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $donation->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Donation
                </button>
                <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .edit-donation-container {
        padding: 2rem;
    }

    .donation-form {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-dark);
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-text {
        padding: 0.75rem;
        background-color: var(--background-light);
        border: 1px solid var(--border);
        border-right: none;
        border-radius: 4px 0 0 4px;
    }

    .input-group .form-control {
        border-radius: 0 4px 4px 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border: none;
        font-size: 1rem;
    }

    .btn-primary {
        background-color: var(--primary-dark);
        color: var(--white);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: var(--white);
    }
</style>
@endpush 