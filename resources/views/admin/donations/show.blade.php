@extends('layouts.gkc')

@section('title', 'Donation Details')

@section('content')
    <div class="donation-details-container">
        <div class="donation-header">
            <div class="header-content">
                <h1>Donation Details</h1>
                <p class="subtitle">View donation information</p>
            </div>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Donations
            </a>
        </div>

        <div class="donation-details-card">
            <div class="donation-header">
                <div class="donation-title">
                    <h2>Donation #{{ $donation->id }}</h2>
                    <span class="status-badge status-{{ $donation->status }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
                <div class="donation-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}
                </div>
            </div>

            <div class="donation-sections">
                <div class="donation-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user"></i> Donor Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-item">
                            <span class="info-label">Donor Name</span>
                            <span class="info-value">{{ $donation->user ? $donation->user->name : $donation->donor_name }}</span>
                        </div>
                    </div>
                </div>

                <div class="donation-section">
                    <div class="section-header">
                        <h3><i class="fas fa-money-bill-wave"></i> Transaction Details</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-item">
                            <span class="info-label">Amount</span>
                            <span class="info-value amount">₱{{ number_format($donation->amount, 2) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Purpose</span>
                            <span class="info-value">{{ ucfirst($donation->purpose) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">{{ ucfirst($donation->payment_method) }}</span>
                        </div>
                    </div>
                </div>

                <div class="donation-section">
                    <div class="section-header">
                        <h3><i class="fas fa-user-shield"></i> Admin Details</h3>
                    </div>
                    <div class="section-content">
                        <div class="info-item">
                            <span class="info-label">Admin Response</span>
                            <span class="info-value">{{ $donation->admin_response ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Verification Notes</span>
                            <span class="info-value">{{ $donation->verification_notes ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Verified By</span>
                            <span class="info-value">{{ $donation->verified_by ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Verification Date</span>
                            <span class="info-value">{{ $donation->verification_date ? $donation->verification_date->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($donation->status === 'pending')
                <div class="donation-actions">
                    <form action="{{ route('admin.donations.approve', $donation) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.donations.decline', $donation) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="admin_response" value="Declined by admin">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Decline
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .donation-details-container {
        padding: 2rem;
    }

    .donation-header {
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

    .donation-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background-color: #f7fafc;
    }

    .donation-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .donation-title h2 {
        margin: 0;
        color: #1a365d;
        font-size: 1.25rem;
    }

    .donation-date {
        color: #4a5568;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .donation-sections {
        padding: 1.5rem;
    }

    .donation-section {
        margin-bottom: 2rem;
    }

    .donation-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        margin-bottom: 1rem;
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
        background: #f8fafc;
        border-radius: 0.375rem;
        padding: 1.25rem;
    }

    .info-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        flex: 0 0 200px;
        color: #4a5568;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
        color: #2d3748;
    }

    .info-value.amount {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2b6cb0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-badge i {
        font-size: 0.75rem;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-declined {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .donation-actions {
        padding: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
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