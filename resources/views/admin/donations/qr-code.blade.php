@extends('layouts.gkc')

@section('title', 'GCash QR Code')

@section('content')
<div class="qr-code-container">
    <div class="qr-code-header">
        <h1>GCash QR Code for Donations</h1>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Donations
        </a>
    </div>

    <div class="qr-code-card">
        <div class="qr-code-content">
            <div class="qr-image-container">
                <img src="{{ asset('images/sample QR.png') }}" alt="GCash QR Code" class="qr-image">
            </div>
            
            <div class="qr-instructions">
                <h3>Scan to Donate</h3>
                <p>Use your GCash app to scan this QR code</p>
                
                <div class="instructions-list">
                    <h4>Donation Instructions:</h4>
                    <ol>
                        <li>Open your GCash app</li>
                        <li>Tap on "Scan QR"</li>
                        <li>Scan the QR code above</li>
                        <li>Enter the donation amount</li>
                        <li>Add a note with your name</li>
                        <li>Confirm the transaction</li>
                    </ol>
                </div>

                <div class="qr-note">
                    <i class="fas fa-info-circle"></i>
                    <p>After making your donation, please wait for verification. You will receive a notification once your donation has been verified.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.qr-code-container {
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
}

.qr-code-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.qr-code-header h1 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.875rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: var(--primary-light);
    color: var(--primary-dark);
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-back:hover {
    background-color: var(--primary);
    color: white;
}

.qr-code-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.qr-code-content {
    padding: 2rem;
    text-align: center;
}

.qr-image-container {
    margin-bottom: 2rem;
    padding: 1rem;
    background: var(--background-light);
    border-radius: 0.5rem;
    display: inline-block;
}

.qr-image {
    max-width: 300px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.qr-instructions {
    text-align: left;
    max-width: 600px;
    margin: 0 auto;
}

.qr-instructions h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
}

.qr-instructions p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.instructions-list {
    background: var(--background-light);
    padding: 1.5rem;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
}

.instructions-list h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-size: 1.25rem;
}

.instructions-list ol {
    padding-left: 1.5rem;
    color: var(--text-secondary);
}

.instructions-list li {
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

.qr-note {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--primary-light);
    border-radius: 0.5rem;
    color: var(--primary-dark);
}

.qr-note i {
    font-size: 1.25rem;
    color: var(--primary);
}

.qr-note p {
    margin: 0;
    text-align: left;
}

@media (max-width: 768px) {
    .qr-code-container {
        padding: 1rem;
    }

    .qr-code-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .qr-code-header h1 {
        font-size: 1.5rem;
    }

    .qr-image {
        max-width: 250px;
    }

    .qr-instructions {
        padding: 0 1rem;
    }
}
</style>
@endpush 
