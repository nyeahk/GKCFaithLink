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

        <div class="donation-cards-grid">
            <!-- Donor Information Card -->
            <div class="donation-card donor-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h2>Donor Information</h2>
                </div>
                <div class="card-content">
                    @if($donation->user_id)
                        <div class="donor-profile">
                            <div class="profile-header">
                                @if($donation->user->profile_photo)
                                    <img src="{{ asset('storage/' . $donation->user->profile_photo) }}" 
                                         alt="{{ $donation->user->name }}" 
                                         class="profile-photo">
                                @else
                                    <div class="profile-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div class="profile-info">
                                    <h3>{{ $donation->user->name }}</h3>
                                    <span class="member-since">Member since {{ $donation->user->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                            <div class="donor-details">
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <div class="detail-content">
                                        <label>Email</label>
                                        <span>{{ $donation->user->email }}</span>
                                    </div>
                                </div>
                                @if($donation->user->phone)
                                    <div class="detail-item">
                                        <i class="fas fa-phone"></i>
                                        <div class="detail-content">
                                            <label>Phone</label>
                                            <span>{{ $donation->user->phone }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($donation->user->address)
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="detail-content">
                                            <label>Address</label>
                                            <span>{{ $donation->user->address }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="profile-actions">
                                <a href="{{ route('members.show', ['id' => $donation->user_id]) }}" class="btn btn-view-profile">
                                    <i class="fas fa-user"></i> View Full Profile
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="donor-details">
                            <div class="detail-item">
                                <i class="fas fa-user"></i>
                                <div class="detail-content">
                                    <label>Name</label>
                                    <span>{{ $donation->donor_name }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Details Card -->
            <div class="donation-card transaction-card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i>
                    <h2>Transaction Details</h2>
                </div>
                <div class="card-content">
                    <div class="detail-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <div class="detail-content">
                            <label>Amount</label>
                            <span class="amount">₱{{ number_format($donation->amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-bullseye"></i>
                        <div class="detail-content">
                            <label>Purpose</label>
                            <span>{{ ucfirst($donation->purpose) }}</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-credit-card"></i>
                        <div class="detail-content">
                            <label>Payment Method</label>
                            <span>{{ $donation->payment_method }}</span>
                        </div>
                    </div>
                    @if($donation->reference_number)
                        <div class="detail-item">
                            <i class="fas fa-hashtag"></i>
                            <div class="detail-content">
                                <label>Reference Number</label>
                                <span>{{ $donation->reference_number }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="detail-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="detail-content">
                            <label>Transaction Date</label>
                            <span>{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="donation-card status-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h2>Status Information</h2>
                </div>
                <div class="card-content">
                    <div class="status-display">
                        <span class="status-badge status-{{ $donation->status }}">
                            <i class="fas fa-circle"></i>
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                    @if($donation->admin)
                        <div class="detail-item">
                            <i class="fas fa-user-shield"></i>
                            <div class="detail-content">
                                <label>Processed By</label>
                                <span>{{ $donation->admin->name }}</span>
                            </div>
                        </div>
                    @endif
                    @if($donation->admin_response)
                        <div class="detail-item">
                            <i class="fas fa-comment-alt"></i>
                            <div class="detail-content">
                                <label>Admin Response</label>
                                <span>{{ $donation->admin_response }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Card -->
            @if($donation->verification_notes)
                <div class="donation-card verification-card">
                    <div class="card-header">
                        <i class="fas fa-check-circle"></i>
                        <h2>Verification Details</h2>
                    </div>
                    <div class="card-content">
                        <div class="verification-notes">
                            <p>{{ $donation->verification_notes }}</p>
                        </div>
                        @if($donation->verified_by)
                            <div class="verification-meta">
                                <div class="detail-item">
                                    <i class="fas fa-user-check"></i>
                                    <span>Verified by: {{ $donation->verified_by }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Date: {{ $donation->verification_date->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Payment Screenshot Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-image"></i>
                    <h3>Payment Screenshot</h3>
                </div>
                <div class="card-body">
                    @if($donation->screenshot)
                        <div class="screenshot-container">
                            <a href="{{ asset('storage/' . $donation->screenshot) }}" class="screenshot-link" data-lightbox="payment-screenshot">
                                <img src="{{ asset('storage/' . $donation->screenshot) }}" 
                                     alt="Payment Screenshot" 
                                     class="screenshot-image"
                                     loading="lazy">
                                <div class="screenshot-overlay">
                                    <div class="overlay-content">
                                        <i class="fas fa-search-plus"></i>
                                        <span>Click to view full size</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="no-screenshot">
                            <i class="fas fa-image"></i>
                            <p>No screenshot provided</p>
                        </div>
                    @endif
                </div>
            </div>
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

    .donation-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .donation-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .donation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--accent-teal));
        color: white;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        font-size: 1.5rem;
    }

    .card-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-content {
        padding: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-item i {
        color: var(--accent-teal);
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
    }

    .detail-content {
        flex: 1;
    }

    .detail-content label {
        display: block;
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .detail-content span {
        color: #2d3748;
        font-weight: 500;
    }

    .amount {
        color: var(--primary-dark);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .donor-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--primary-dark);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .donor-link:hover {
        color: var(--accent-teal);
    }

    .donor-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .verification-notes {
        background-color: #f7fafc;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--accent-teal);
        margin-bottom: 1rem;
    }

    .verification-notes p {
        margin: 0;
        color: #2d3748;
        line-height: 1.5;
    }

    .verification-meta {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .screenshot-container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        background: #f8f9fa;
        min-height: 200px;
    }

    .screenshot-link {
        display: block;
        position: relative;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .screenshot-image {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-height: 400px;
        margin: 0 auto;
    }

    .screenshot-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .overlay-content {
        text-align: center;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }

    .screenshot-overlay i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .screenshot-overlay span {
        font-size: 1rem;
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        display: block;
    }

    .screenshot-link:hover .screenshot-overlay {
        opacity: 1;
    }

    .screenshot-link:hover .overlay-content {
        transform: translateY(0);
    }

    .screenshot-link:hover .screenshot-image {
        transform: scale(1.05);
    }

    .no-screenshot {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #dee2e6;
    }

    .no-screenshot i {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: #dee2e6;
        opacity: 0.7;
    }

    .no-screenshot p {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .status-display {
        margin-bottom: 1.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
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
        justify-content: center;
    }

    .btn-approve {
        background-color: #10b981;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
    }

    .btn-approve:hover {
        background-color: #059669;
        transform: translateY(-2px);
    }

    .btn-decline {
        background-color: #ef4444;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
    }

    .btn-decline:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 2rem;
        border-radius: 1rem;
        width: 50%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-header h3 {
        margin: 0;
        color: #1a365d;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #718096;
    }

    .modal-body {
        margin-bottom: 1.5rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .donation-cards-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 90%;
            margin: 10% auto;
        }
    }

    /* Lightbox Customization */
    .lb-data .lb-caption {
        font-size: 1rem;
        font-weight: 500;
    }

    .lb-data .lb-number {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .lb-nav a.lb-prev,
    .lb-nav a.lb-next {
        opacity: 0.9;
    }

    .lb-nav a.lb-prev:hover,
    .lb-nav a.lb-next:hover {
        opacity: 1;
    }

    .lb-outerContainer {
        border-radius: 8px;
    }
    
    .lb-image {
        border-radius: 4px;
    }

    .donor-profile {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .profile-photo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent-teal);
    }

    .profile-photo-placeholder {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--accent-teal);
    }

    .profile-photo-placeholder i {
        font-size: 1.5rem;
        color: #718096;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h3 {
        margin: 0;
        color: var(--primary-dark);
        font-size: 1.25rem;
    }

    .member-since {
        font-size: 0.875rem;
        color: #718096;
    }

    .donor-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .detail-item i {
        color: var(--accent-teal);
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
        margin-top: 0.25rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-content label {
        display: block;
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .detail-content span {
        color: #2d3748;
        font-weight: 500;
    }

    .profile-actions {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view-profile {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: var(--accent-teal);
        color: white;
        border-radius: 0.375rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-view-profile:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }
</style>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
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
    window.onclick = function(event) {
        const modal = document.getElementById('declineModal');
        if (event.target == modal) {
            closeDeclineModal();
        }
    }

    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Image %1 of %2',
        'fadeDuration': 300,
        'imageFadeDuration': 300,
        'showImageNumberLabel': true,
        'alwaysShowNavOnTouchDevices': true
    });
</script>
@endpush 