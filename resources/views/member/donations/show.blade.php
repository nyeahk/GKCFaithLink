@extends('layouts.member')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.donations.index') }}">My Donations</a></li>
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
                        <a href="{{ route('member.donations.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left column - Donation details -->
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
                                    <div class="col-md-8 fw-medium">{{ ucfirst($donation->purpose ?? 'General') }}</div>
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
                                    <div class="col-md-4 text-muted">Date Submitted:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                
                                @if($donation->notes)
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Your Notes:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->notes }}</div>
                                </div>
                                @endif
                                
                                @if($donation->status == 'verified' && $donation->verification_date)
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Verified By:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->verified_by }}</div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Verification Date:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->verification_date->format('F d, Y h:i A') }}</div>
                                </div>
                                
                                @if($donation->verification_notes)
                                <div class="row mb-2">
                                    <div class="col-md-4 text-muted">Verification Notes:</div>
                                    <div class="col-md-8 fw-medium">{{ $donation->verification_notes }}</div>
                                </div>
                                @endif
                                @endif
                                
                                @if($donation->status == 'declined')
                                <div class="alert alert-danger mt-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="bi bi-x-circle-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-heading">Donation Declined</h6>
                                            <p class="mb-0">
                                                @if($donation->verification_notes)
                                                    <strong>Reason:</strong> {{ $donation->verification_notes }}
                                                @else
                                                    Your donation has been declined. Please contact the church office for more information.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right column - Screenshot and verification status -->
                        <div class="col-md-5">
                            <!-- Screenshot -->
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
                            
                            <!-- Pending notification -->
                            @if($donation->status == 'pending')
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-info-circle-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading">Pending Verification</h6>
                                        <p class="mb-0">Your donation is currently being reviewed by our treasurer. You will be notified once it has been verified.</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Verified notification -->
                            @if($donation->status == 'verified')
                            <div class="alert alert-success mt-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading">Donation Verified by {{ $donation->verified_by }}</h6>
                                        <p class="mb-0">Thank you for your generous contribution! Your donation has been verified and recorded.</p>
                                        @if($donation->verification_notes)
                                            <p class="mt-2 mb-0"><strong>Note:</strong> {{ $donation->verification_notes }}</p>
                                        @endif
                                    </div>
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
@endsection

@push('styles')
<style>
    .donation-amount-badge {
        width: 50px;
        height: 50px;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #0d6efd;
    }
    
    .section-title {
        color: #495057;
        font-size: 1rem;
    }
    
    .screenshot-container {
        position: relative;
        display: inline-block;
        max-width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .screenshot-link {
        display: block;
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
        transition: opacity 0.3s;
    }
    
    .screenshot-container:hover .screenshot-overlay {
        opacity: 1;
    }
    
    .screenshot-overlay i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .badge {
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
        font-size: 1.5rem;
        color: #6c757d;
    }
</style>
@endpush


