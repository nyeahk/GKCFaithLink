@extends('layouts.gkc')

@section('title', 'Add Manual Donation')

@section('content')
    <div class="manual-donation-container">
        <div class="manual-donation-header">
            <h1>Add Manual Donation</h1>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Donations
            </a>
        </div>

        <div class="manual-donation-form">
            <form action="{{ route('admin.donations.manual-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="donor_name">Donor's Name</label>
                    <input type="text" name="donor_name" id="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name') }}" required>
                    @error('donor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose of Donation</label>
                    <select name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" required>
                        <option value="">Select Purpose</option>
                        <option value="tithes" {{ old('purpose') == 'tithes' ? 'selected' : '' }}>Tithes</option>
                        <option value="offering" {{ old('purpose') == 'offering' ? 'selected' : '' }}>Offering</option>
                        <option value="mission" {{ old('purpose') == 'mission' ? 'selected' : '' }}>Mission</option>
                    </select>
                    @error('purpose')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                        <option value="">Select Payment Method</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online_payment" {{ old('payment_method') == 'online_payment' ? 'selected' : '' }}>Online Payment</option>
                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="reference_number">Reference Number (Optional)</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}">
                    @error('reference_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="screenshot">Screenshot (Optional)</label>
                    <input type="file" name="screenshot" id="screenshot" class="form-control @error('screenshot') is-invalid @enderror" accept="image/*">
                    @error('screenshot')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                <div class="form-group">
                    <label for="transaction_date">Transaction Date</label>
                    <input type="datetime-local" name="transaction_date" id="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date') }}" required>
                    @error('transaction_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="verification_notes_field" style="display: none;">
                    <div class="form-group">
                        <label for="verification_notes">Verification Notes</label>
                        <textarea name="verification_notes" id="verification_notes" class="form-control @error('verification_notes') is-invalid @enderror" rows="3">{{ old('verification_notes') }}</textarea>
                        @error('verification_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Donation
                    </button>
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .manual-donation-container {
        padding: 2rem;
    }

    .manual-donation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .manual-donation-header h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: #e2e8f0;
        color: #4a5568;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background-color: #cbd5e0;
    }

    .manual-donation-form {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .is-invalid {
        border-color: #e53e3e;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-text {
        padding: 0.75rem;
        background-color: #f7fafc;
        border: 1px solid #e2e8f0;
        border-right: none;
        border-radius: 0.375rem 0 0 0.375rem;
    }

    .input-group .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #3182ce;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background-color: #cbd5e0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const status = document.getElementById('status');
        const verificationNotesField = document.getElementById('verification_notes_field');

        status.addEventListener('change', function() {
            verificationNotesField.style.display = this.value === 'verified' ? 'block' : 'none';
        });

        // Trigger change event on page load
        status.dispatchEvent(new Event('change'));
    });
</script>
@endpush 