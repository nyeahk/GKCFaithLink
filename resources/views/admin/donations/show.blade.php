@extends('layouts.gkc')

@section('title', 'Donation Details')

@section('content')
    <div class="donation-details-container">
        <div class="donation-details-header">
            <h1>Donation Details</h1>
            <a href="{{ route('admin.donations.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Donations
            </a>
        </div>

        <div class="donation-details-card">
            <div class="donation-info">
                <div class="info-group">
                    <label>Donor</label>
                    <div class="value">
                        @if($donation->user_id)
                            <a href="{{ route('members.show', ['id' => $donation->user_id]) }}" class="donor-link">
                                <i class="fas fa-user"></i>
                                {{ $donation->user->name }}
                            </a>
                        @else
                            <span class="donor-link">
                                <i class="fas fa-user"></i>
                                {{ $donation->donor_name }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="info-group">
                    <label>Amount</label>
                    <div class="value">₱{{ number_format($donation->amount, 2) }}</div>
                </div>

                <div class="info-group">
                    <label>Purpose</label>
                    <div class="value">{{ ucfirst($donation->purpose) }}</div>
                </div>

                @if($donation->reference_number)
                    <div class="info-group">
                        <label>Reference Number</label>
                        <div class="value">{{ $donation->reference_number }}</div>
                    </div>
                @endif

                @if($donation->screenshot)
                    <div class="info-group">
                        <label>Screenshot</label>
                        <div class="value">
                            <a href="{{ asset('storage/' . $donation->screenshot) }}" target="_blank">
                                <img src="{{ asset('storage/' . $donation->screenshot) }}" alt="Donation Screenshot" style="max-width: 300px; border-radius: 0.375rem;">
                            </a>
                        </div>
                    </div>
                @endif

                <div class="info-group">
                    <label>Payment Method</label>
                    <div class="value">{{ $donation->payment_method }}</div>
                </div>

                <div class="info-group">
                    <label>Status</label>
                    <div class="value">
                        <span class="status-badge status-{{ $donation->status }}">
                            <i class="fas fa-circle"></i>
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>

                <div class="info-group">
                    <label>Transaction Date</label>
                    <div class="value">{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}</div>
                </div>

                @if($donation->admin_response)
                    <div class="info-group">
                        <label>Admin Response</label>
                        <div class="value">{{ $donation->admin_response }}</div>
                    </div>
                @endif

                @if($donation->admin)
                    <div class="info-group">
                        <label>Processed By</label>
                        <div class="value">{{ $donation->admin->name }}</div>
                    </div>
                @endif
            </div>

            @if($donation->status === 'pending')
                <div class="donation-actions">
                    <form action="{{ route('admin.donations.approve', $donation) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-approve">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>

                    <button type="button" class="btn btn-decline" onclick="showDeclineModal({{ $donation->id }})">
                        <i class="fas fa-times"></i> Decline
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Decline Modal -->
    <div class="modal" id="declineModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Decline Donation</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form id="declineForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_response">Reason for Declining</label>
                        <textarea id="admin_response" name="admin_response" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeclineModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline Donation</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .donation-details-container {
        padding: 2rem;
    }

    .donation-details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .donation-details-header h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
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
        padding: 2rem;
    }

    .donation-info {
        display: grid;
        gap: 1.5rem;
    }

    .info-group {
        display: grid;
        gap: 0.5rem;
    }

    .info-group label {
        color: #4a5568;
        font-weight: 500;
    }

    .info-group .value {
        color: #1a365d;
        font-size: 1.125rem;
    }

    .donor-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #2b6cb0;
        text-decoration: none;
    }

    .donor-link:hover {
        text-decoration: underline;
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
    }

    .btn-approve {
        background-color: #dcfce7;
        color: #166534;
    }

    .btn-approve:hover {
        background-color: #bbf7d0;
    }

    .btn-decline {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-decline:hover {
        background-color: #fed7d7;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background-color: #cbd5e0;
    }

    .btn-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-danger:hover {
        background-color: #fed7d7;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        position: relative;
        background-color: white;
        margin: 10% auto;
        padding: 0;
        width: 90%;
        max-width: 500px;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        color: #1a365d;
        font-size: 1.25rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #4a5568;
        cursor: pointer;
        padding: 0.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
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
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4299e1;
    }
</style>
@endpush

@push('scripts')
<script>
function showDeclineModal(donationId) {
    const modal = document.getElementById('declineModal');
    const form = document.getElementById('declineForm');
    form.action = `/donations/${donationId}/decline`;
    modal.style.display = 'block';
}

function closeDeclineModal() {
    const modal = document.getElementById('declineModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('declineModal');
    if (event.target === modal) {
        closeDeclineModal();
    }
});

// Close modal when clicking close button
document.querySelector('.modal-close').addEventListener('click', closeDeclineModal);
</script>
@endpush 