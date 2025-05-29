@extends('layouts.gkc')

@section('title', 'Edit Donation')

@section('content')
    <div class="donations-container">
        <div class="donations-header">
            <div class="header-content">
                <h1>Edit Donation</h1>
                <p class="subtitle">Update donation information</p>
            </div>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Donations
            </a>
        </div>

        <div class="donation-details-card">
            <form action="{{ route('admin.donations.update', $donation->id) }}" method="POST" class="donation-form">
                @csrf
                @method('PUT')
                
                <div class="donation-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user"></i> Donor Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="form-group">
                            <label for="donor_name">Donor Name</label>
                            <input type="text" name="donor_name" id="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name', $donation->donor_name) }}" required>
                            @error('donor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="donation-section">
                    <div class="section-header">
                        <h3><i class="fas fa-money-bill-wave"></i> Transaction Details</h3>
                    </div>
                    <div class="section-content">
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
                            <label for="payment_method">Payment Method</label>
                            <input type="text" class="form-control" value="Cash" readonly>
                            <input type="hidden" name="payment_method" value="cash">
                        </div>

                        <div class="form-group">
                            <label for="transaction_date">Transaction Date and Time</label>
                            <input type="datetime-local" name="transaction_date" id="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $donation->transaction_date ? $donation->transaction_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Donation
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
    .donations-container {
        padding: 2rem;
    }

    .donations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-content h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .header-content .subtitle {
        color: #4a5568;
        margin: 0.5rem 0 0;
        font-size: 1rem;
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

    .donation-details-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .donation-section {
        margin-bottom: 2rem;
    }

    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background-color: #f7fafc;
    }

    .section-header h3 {
        color: #2d3748;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-content {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4a5568;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        font-size: 1rem;
        background: #f7fafc;
        color: #2d3748;
    }

    .form-control:focus {
        outline: none;
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
    }

    .form-control[readonly] {
        background-color: #edf2f7;
        cursor: not-allowed;
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
        color: #4a5568;
    }

    .input-group .form-control {
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        border-top: 1px solid #e2e8f0;
        background-color: #f7fafc;
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