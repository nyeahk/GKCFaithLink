@extends('layouts.member')

@section('title', 'View Details')

@section('content')
<div class="container py-4">
    <!-- Back button with enhanced styling -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Enhanced detail card with dashboard color palette -->
    <div class="detail-card">
        <!-- Header section with gradient background -->
        <div class="detail-header">
            <div class="detail-icon-container">
                <div class="detail-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
            <h2 class="detail-title">{{ $item->title ?? 'Item Details' }}</h2>
            <div class="detail-meta">
                <span class="detail-date">
                    <i class="bi bi-calendar3"></i> 
                    {{ isset($item->created_at) ? $item->created_at->format('F d, Y') : 'N/A' }}
                </span>
                <span class="detail-status">
                    <span class="status-badge {{ $item->status_class ?? 'status-default' }}">
                        {{ $item->status ?? 'Status' }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Main content with enhanced styling -->
        <div class="detail-body">
            <!-- Primary information section -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i> Primary Information
                </h3>
                <div class="detail-info-grid">
                    @if(isset($item->id))
                    <div class="detail-info-item">
                        <div class="info-label">ID</div>
                        <div class="info-value">{{ $item->id }}</div>
                    </div>
                    @endif

                    @if(isset($item->reference_number))
                    <div class="detail-info-item">
                        <div class="info-label">Reference Number</div>
                        <div class="info-value">{{ $item->reference_number }}</div>
                    </div>
                    @endif

                    @if(isset($item->type))
                    <div class="detail-info-item">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ $item->type }}</div>
                    </div>
                    @endif

                    @if(isset($item->amount))
                    <div class="detail-info-item">
                        <div class="info-label">Amount</div>
                        <div class="info-value amount">₱{{ number_format($item->amount, 2) }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description section with enhanced styling -->
            @if(isset($item->description))
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-card-text"></i> Description
                </h3>
                <div class="detail-description">
                    {{ $item->description }}
                </div>
            </div>
            @endif

            <!-- Additional details section -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-list-check"></i> Additional Details
                </h3>
                <div class="detail-info-grid">
                    @if(isset($item->category))
                    <div class="detail-info-item">
                        <div class="info-label">Category</div>
                        <div class="info-value">{{ $item->category }}</div>
                    </div>
                    @endif

                    @if(isset($item->payment_method))
                    <div class="detail-info-item">
                        <div class="info-label">Payment Method</div>
                        <div class="info-value">{{ $item->payment_method }}</div>
                    </div>
                    @endif

                    @if(isset($item->transaction_date))
                    <div class="detail-info-item">
                        <div class="info-label">Transaction Date</div>
                        <div class="info-value">{{ $item->transaction_date->format('F d, Y') }}</div>
                    </div>
                    @endif

                    @if(isset($item->updated_at))
                    <div class="detail-info-item">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ $item->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Attachments section with enhanced styling -->
            @if(isset($item->attachments) && count($item->attachments) > 0)
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-paperclip"></i> Attachments
                </h3>
                <div class="attachments-container">
                    @foreach($item->attachments as $attachment)
                    <div class="attachment-item">
                        <div class="attachment-icon">
                            <i class="bi bi-file-earmark"></i>
                        </div>
                        <div class="attachment-info">
                            <div class="attachment-name">{{ $attachment->filename }}</div>
                            <div class="attachment-size">{{ $attachment->size }}</div>
                        </div>
                        <a href="{{ $attachment->url }}" class="attachment-download" download>
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Notes section with enhanced styling -->
            @if(isset($item->notes) && !empty($item->notes))
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-journal-text"></i> Notes
                </h3>
                <div class="detail-notes">
                    {{ $item->notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Footer with action buttons -->
        <div class="detail-footer">
            <div class="detail-actions">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                
                @if(isset($item->status) && $item->status !== 'Completed')
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @endif
                
                @if(isset($item->can_print) && $item->can_print)
                <a href="{{ route('items.print', $item->id) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-printer"></i> Print
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Enhanced detail page styling using dashboard color palette */
    :root {
        --detail-border-radius: 16px;
        --detail-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --detail-transition: all 0.3s ease;
    }
    
    /* Back link styling */
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--text-light);
        text-decoration: none;
        font-weight: 500;
        transition: var(--hover-transition);
        padding: 0.5rem 0;
    }
    
    .back-link:hover {
        color: var(--primary);
        transform: translateX(-3px);
    }
    
    .back-link i {
        margin-right: 0.5rem;
    }
    
    /* Main detail card styling */
    .detail-card {
        background-color: var(--white);
        border-radius: var(--detail-border-radius);
        box-shadow: var(--detail-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Header section with gradient background */
    .detail-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: var(--white);
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
    }
    
    .detail-icon-container {
        margin-bottom: 1.5rem;
    }
    
    .detail-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        backdrop-filter: blur(5px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .detail-icon i {
        font-size: 2.5rem;
        color: var(--white);
    }
    
    .detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .detail-meta {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .detail-date i, 
    .detail-status i {
        margin-right: 0.5rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background-color: var(--warning);
        color: #856404;
    }
    
    .status-approved {
        background-color: var(--success);
        color: #155724;
    }
    
    .status-rejected {
        background-color: var(--error);
        color: #721c24;
    }
    
    .status-default {
        background-color: var(--secondary);
        color: var(--white);
    }
    
    /* Main content styling */
    .detail-body {
        padding: 2rem;
    }
    
    .detail-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-light, #e9ecef);
        animation: fadeInUp 0.6s ease-out;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 0.75rem;
        font-size: 1.3rem;
    }
    
    /* Information grid styling */
    .detail-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .detail-info-item {
        background-color: var(--background-light);
        padding: 1.25rem;
        border-radius: 12px;
        transition: var(--detail-transition);
    }
    
    .detail-info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .info-label {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .info-value.amount {
        color: var(--primary);
        font-size: 1.3rem;
    }
    
    /* Description styling */
    .detail-description {
        background-color: var(--background-light);
        padding: 1.5rem;
        border-radius: 12px;
        line-height: 1.6;
        color: var(--text-dark);
    }
    
    /* Notes styling */
    .detail-notes {
        background-color: var(--background-light);
        padding: 1.5rem;
        border-radius: 12px;
        line-height: 1.6;
        color: var(--text-dark);
        font-style: italic;
        border-left: 4px solid var(--primary-light);
    }
    
    /* Attachments styling */
    .attachments-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .attachment-item {
        display: flex;
        align-items: center;
        background-color: var(--background-light);
        padding: 1rem;
        border-radius: 12px;
        transition: var(--detail-transition);
    }
    
    .attachment-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .attachment-icon {
        width: 40px;
        height: 40px;
        background-color: var(--primary-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .attachment-icon i {
        font-size: 1.2rem;
        color: var(--primary-dark);
    }
    
    .attachment-info {
        flex: 1;
    }
    
    .attachment-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }
    
    .attachment-size {
        font-size: 0.8rem;
        color: var(--text-light);
    }
    
    .attachment-download {
        width: 36px;
        height: 36px;
        background-color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        transition: var(--detail-transition);
    }
    
    .attachment-download:hover {
        background-color: var(--primary-dark);
        transform: scale(1.1);
    }
    
    /* Footer styling */
    .detail-footer {
        background-color: var(--background-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-light, #e9ecef);
    }
    
    .detail-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    /* Button styling to match dashboard */
    .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: var(--detail-transition);
    }
    
    .btn i {
        margin-right: 0.5rem;
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--white);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(79, 149, 157, 0.3);
    }
    
    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
        background-color: transparent;
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary);
        color: var(--white);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(79, 149, 157, 0.3);
    }
    
    .btn-success {
        background-color: var(--success);
        border-color: var(--success);
        color: var(--white);
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    
    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .detail-info-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-meta {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .detail-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endpush