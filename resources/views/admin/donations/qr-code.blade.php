@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>GCash QR Code for Donations</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img src="{{ asset('images/gcash-qr.png') }}" alt="GCash QR Code" class="img-fluid" style="max-width: 300px;">
                    </div>
                    
                    <div class="mb-4">
                        <h4>Scan to Donate</h4>
                        <p class="text-muted">Use your GCash app to scan this QR code</p>
                    </div>

                    <div class="mb-4">
                        <h5>Donation Instructions:</h5>
                        <ol class="text-left">
                            <li>Open your GCash app</li>
                            <li>Tap on "Scan QR"</li>
                            <li>Scan the QR code above</li>
                            <li>Enter the donation amount</li>
                            <li>Add a note with your name</li>
                            <li>Confirm the transaction</li>
                        </ol>
                    </div>

                    <div class="alert alert-info">
                        <p class="mb-0">After making your donation, please wait for verification. You will receive a notification once your donation has been verified.</p>
                    </div>

                    <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Back to Donations</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 