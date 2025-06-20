@extends('layouts.treasurer')

@section('title', 'Add Manual Donation')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('treasurer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('treasurer.donations.index') }}">Donations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Manual Donation</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add Manual Donation</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('treasurer.donations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="donor_name">Donor's Name</label>
                                    <input type="text" name="donor_name" id="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name') }}" required>
                                    @error('donor_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount (₱)</label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" step="0.01" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purpose">Purpose</label>
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Check details (only shown when check is selected) -->
                        <div id="check_details" class="row mb-3" style="{{ old('payment_method') == 'check' ? '' : 'display: none;' }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="check_number">Check Number</label>
                                    <input type="text" name="check_number" id="check_number" class="form-control @error('check_number') is-invalid @enderror" value="{{ old('check_number') }}">
                                    @error('check_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="check_date">Check Date</label>
                                    <input type="date" name="check_date" id="check_date" class="form-control @error('check_date') is-invalid @enderror" value="{{ old('check_date') }}">
                                    @error('check_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden field for transaction_date -->
                        <input type="hidden" name="transaction_date" value="{{ now()->format('Y-m-d H:i:s') }}">
                        <!-- Hidden field for status - always verified for manual donations -->
                        <input type="hidden" name="status" value="verified">
                        <!-- Hidden field for verification details -->
                        <input type="hidden" name="verified_by" value="{{ auth()->user()->name }}">
                        <input type="hidden" name="verification_date" value="{{ now()->format('Y-m-d H:i:s') }}">

                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Donation
                            </button>
                            <a href="{{ route('treasurer.donations.index') }}" class="btn btn-primary">Cancel</a>
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
        const paymentMethodSelect = document.getElementById('payment_method');
        const checkDetailsDiv = document.getElementById('check_details');
        
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'check') {
                checkDetailsDiv.style.display = 'flex';
                
                // Make check fields required
                document.getElementById('check_number').setAttribute('required', 'required');
                document.getElementById('bank_name').setAttribute('required', 'required');
                document.getElementById('check_date').setAttribute('required', 'required');
            } else {
                checkDetailsDiv.style.display = 'none';
                
                // Remove required attribute from check fields
                document.getElementById('check_number').removeAttribute('required');
                document.getElementById('bank_name').removeAttribute('required');
                document.getElementById('check_date').removeAttribute('required');
            }
        });
    });
</script>
@endpush


