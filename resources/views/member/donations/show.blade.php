@extends('layouts.member')

@section('title', 'Donation Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.donations.index') }}">My Donations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Donation Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Donation Details</h5>
                    <a href="{{ route('member.donations.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Donations
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Amount:</div>
                        <div class="col-md-8">₱{{ number_format($donation->amount, 2) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Purpose:</div>
                        <div class="col-md-8">{{ ucfirst($donation->purpose) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Payment Method:</div>
                        <div class="col-md-8">{{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Reference Number:</div>
                        <div class="col-md-8">{{ $donation->reference_number }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Transaction Date:</div>
                        <div class="col-md-8">{{ $donation->transaction_date->format('F d, Y h:i A') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            @if($donation->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($donation->status == 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($donation->status == 'declined')
                                <span class="badge bg-danger">Declined</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($donation->admin_response)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Admin Response:</div>
                        <div class="col-md-8">{{ $donation->admin_response }}</div>
                    </div>
                    @endif
                    
                    @if($donation->notes)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Notes:</div>
                        <div class="col-md-8">{{ $donation->notes }}</div>
                    </div>
                    @endif
                    
                    @if($donation->screenshot)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Payment Screenshot</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $donation->screenshot) }}" 
                                         alt="Payment Screenshot" 
                                         class="img-fluid rounded" 
                                         style="max-height: 400px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        border-bottom: 0;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush

