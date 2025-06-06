@extends('layouts.treasurer')

@section('title', 'Donation Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('treasurer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('treasurer.donations.index') }}">Donations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Donation #{{ $donation->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Donation Details #{{ $donation->id }}</h5>
                    <div>
                        <a href="{{ route('treasurer.donations.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left column - Donation and donor details -->
                        <div class="col-md-7">
                            <!-- Donation status and amount -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="donation-amount-badge me-3">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Amount</div>
                                        <div class="fs-3 fw-bold">₱{{ number_format($donation->amount, 2) }}</div>
                                    </div>
                                </div>
                                
                                <div class="donation-status-container mb-3">
                                    <div class="text-muted small mb-1">Status</div>
                                    <div>
                                        @if($donation->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-hourglass-split me-1"></i> Pending
                                            </span>
                                        @elseif($donation->status == 'verified')
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i> Verified
                                            </span>
                                        @elseif($donation->status == 'declined')
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-x-circle me-1"></i> Declined
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Donation information -->
                            <div class="donation-info-section mb-4">
                                <h6 class="section-title border-bottom pb-2 mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Donation Information
                                </h6>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Purpose:</div>
                                    <div class="col-md-8 fw-medium">{{ ucfirst($donation->purpose) }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Payment Method:</div>
                                    <div class="col-md-8 fw-medium">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Reference Number:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->reference_number ?: 'N/A' }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Transaction Date:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->transaction_date->format('F d, Y h:i A') }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Submitted On:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                
                                @if($donation->notes)
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Notes:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->notes }}</div>
                                </div>
                                @endif
                                
                                @if($donation->status == 'verified')
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Verified By:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->verified_by }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Verification Date:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->verification_date->format('F d, Y h:i A') }}</div>
                                </div>
                                @endif
                                
                                @if($donation->status == 'declined' && $donation->treasurer_response)
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Decline Reason:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->treasurer_response }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Donor information -->
                            <div class="donor-info-section">
                                <h6 class="section-title border-bottom pb-2 mb-3">
                                    <i class="bi bi-person-circle me-2"></i>Donor Information
                                </h6>
                                
                                @if($donation->user_id)
                                <div class="donor-profile d-flex align-items-center mb-3">
                                    <div class="donor-avatar me-3">
                                        @if($donation->user->image_path)
                                            <img src="{{ asset('storage/' . $donation->user->image_path) }}" 
                                                alt="{{ $donation->user->name }}" 
                                                class="rounded-circle" width="60" height="60">
                                        @else
                                            <div class="avatar-placeholder rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="bi bi-person-fill" style="font-size: 1.5rem; color: #6c757d;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $donation->user->name }}</h6>
                                        <p class="text-muted mb-0 small">Member since {{ $donation->user->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Email:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->user->email }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Phone:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->user->contact_number ?? 'N/A' }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Address:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->user->address ?? 'N/A' }}</div>
                                </div>
                                @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    This donation was manually added by the treasurer.
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right column - Screenshots and receipt -->
                        <div class="col-md-5">
                            <!-- Payment proof -->
                            @if($donation->screenshot)
                            <div class="mb-4">
                                <h6 class="section-title border-bottom pb-2 mb-3">
                                    <i class="bi bi-image me-2"></i>Payment Screenshot
                                </h6>
                                <div class="text-center mb-3">
                                    <div class="screenshot-container">
                                        <a href="{{ asset('storage/' . $donation->screenshot) }}" target="_blank" class="screenshot-link">
                                            <img src="{{ asset('storage/' . $donation->screenshot) }}" 
                                                alt="Payment Screenshot" 
                                                class="img-fluid rounded shadow-sm">
                                            <div class="screenshot-overlay">
                                                <i class="bi bi-zoom-in"></i>
                                                <span>Click to enlarge</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Receipt image -->
                            @if($donation->receipt_image)
                            <div class="mb-4">
                                <h6 class="section-title border-bottom pb-2 mb-3">
                                    <i class="bi bi-receipt me-2"></i>Receipt Image
                                </h6>
                                <div class="text-center mb-3">
                                    <div class="screenshot-container">
                                        <a href="{{ asset('storage/' . $donation->receipt_image) }}" target="_blank" class="screenshot-link">
                                            <img src="{{ asset('storage/' . $donation->receipt_image) }}" 
                                                alt="Receipt Image" 
                                                class="img-fluid rounded shadow-sm">
                                            <div class="screenshot-overlay">
                                                <i class="bi bi-zoom-in"></i>
                                                <span>Click to enlarge</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Verification actions for pending donations -->
                            @if($donation->status == 'pending')
                            <div class="verification-actions mt-4">
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-heading">Verification Required</h6>
                                            <p class="mb-0">Please verify this donation by checking the payment details and screenshot.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                        <i class="bi bi-check-circle me-2"></i> Verify Donation
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal">
                                        <i class="bi bi-x-circle me-2"></i> Decline Donation
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('treasurer.donations.verify', $donation->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModalLabel">Verify Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to verify this donation of <strong>₱{{ number_format($donation->amount, 2) }}</strong>?</p>
                    
                    <input type="hidden" name="status" value="verified">
                    
                    <div class="mb-3">
                        <label for="verification_notes" class="form-label">Verification Notes (Optional)</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes" rows="3" placeholder="Add any notes about this verification"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Verify Donation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('treasurer.donations.verify', $donation->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Decline Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to decline this donation of <strong>₱{{ number_format($donation->amount, 2) }}</strong>?</p>
                    
                    <input type="hidden" name="status" value="declined">
                    
                    <div class="mb-3">
                        <label for="verification_notes" class="form-label">Reason for Declining (Required)</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes" rows="3" placeholder="Please provide a reason for declining this donation" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline Donation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-title {
        color: #495057;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .donation-amount-badge {
        width: 60px;
        height: 60px;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .donation-amount-badge i {
        font-size: 1.75rem;
        color: #0d6efd;
    }
    
    .fw-medium {
        font-weight: 500;
    }
    
    .avatar-placeholder {
        width: 60px;
        height: 60px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
    }
    
    .screenshot-container {
        position: relative;
        display: inline-block;
        max-width: 100%;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .screenshot-link {
        display: block;
        position: relative;
    }
    
    .screenshot-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .screenshot-overlay i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .screenshot-container:hover .screenshot-overlay {
        opacity: 1;
    }
    
    .screenshot-container img {
        transition: transform 0.3s ease;
        max-height: 400px;
        width: auto;
    }
    
    .screenshot-container:hover img {
        transform: scale(1.02);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    .badge {
        font-weight: 500;
    }
</style>
@endpush



