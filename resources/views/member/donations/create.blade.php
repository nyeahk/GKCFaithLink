@extends('layouts.member')

@section('title', 'Make a Donation')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('member.donations.index') }}">My Donations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Make a Donation</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Make a Donation</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('member.donations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₱)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" min="1" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                            <div class="form-text">Enter the amount you wish to donate.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <select class="form-select" id="purpose" name="purpose" required>
                                <option value="" selected disabled>Select purpose</option>
                                <option value="tithes" {{ old('purpose') == 'tithes' ? 'selected' : '' }}>Tithes</option>
                                <option value="offering" {{ old('purpose') == 'offering' ? 'selected' : '' }}>Offering</option>
                                <option value="mission" {{ old('purpose') == 'mission' ? 'selected' : '' }}>Mission</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="" selected disabled>Select payment method</option>
                                <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            </select>
                        </div>

                        <div id="payment_details" class="mb-3 {{ old('payment_method') ? 'd-block' : 'd-none' }}">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Payment Instructions</h6>
                                    <div id="gcash_instructions">
                                        <p>Please send your donation to our GCash account:</p>
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="qr-code-container text-center">
                                                <img src="{{ asset('images/sample QR.png') }}" alt="GCash QR Code" class="img-fluid" style="max-width: 200px;">
                                                <p class="mt-2 mb-0"><strong>GCash Name:</strong> Church Account</p>
                                                <p class="mb-0"><strong>GCash Number:</strong> 09123456789</p>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Important:</strong> After making your payment, please copy the GCash transaction reference number. You'll need to enter it below.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reference_number" class="form-label">GCash Transaction Reference Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number') }}" required>
                                <div class="form-text">Enter the reference number from your GCash transaction (e.g., GC123456789).</div>
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="screenshot" class="form-label">Payment Screenshot <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('screenshot') is-invalid @enderror" 
                                       id="screenshot" name="screenshot" accept="image/*" required>
                                <div class="form-text">Upload a screenshot of your payment confirmation from GCash.</div>
                                @error('screenshot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d H:i:s') }}">
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('member.donations.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Donation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle payment method selection
        const paymentMethodSelect = document.getElementById('payment_method');
        const paymentDetails = document.getElementById('payment_details');
        
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'gcash') {
                paymentDetails.style.display = 'block';
            } else {
                paymentDetails.style.display = 'none';
            }
        });
    });
</script>
@endpush














