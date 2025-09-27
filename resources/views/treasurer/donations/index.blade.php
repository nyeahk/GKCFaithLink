@extends('layouts.treasurer')

@section('title', 'Donations')

@section('content')
<div class="container-fluid">
    <!-- Clean Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Donations</h2>
                    <p class="text-muted mb-0">Manage church donations</p>
                </div>
                <div>
                    <a href="{{ route('treasurer.donations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Donation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.donations.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select name="status" id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="verified" {{ request('status')=='verified' ? 'selected' : '' }}>Verified</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="declined" {{ request('status')=='declined' ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="dateFilter" class="form-label">Month</label>
                    <input type="month" name="date" id="dateFilter" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <label for="searchFilter" class="form-label">Search</label>
                    <input type="text" name="search" id="searchFilter" class="form-control" 
                           value="{{ request('search') }}" placeholder="Search donor name...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request()->anyFilled(['status','date','search']))
                            <a href="{{ route('treasurer.donations.index') }}" class="btn btn-outline-secondary">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Clean Donations Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Donations List</h6>
            <span class="badge bg-primary">{{ $donations->total() }} Total</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Donor</th>
                            <th>Amount</th>
                            <th>Purpose</th>
                            <th>Reference</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td>
                                    @if($donation->anonymous)
                                        <span class="text-muted">
                                            <i class=""></i>Anonymous
                                        </span>
                                    @else
                                        @if($donation->user)
                                            <span class="fw-medium">{{ $donation->user->getFullNameAttribute() }}</span>
                                        @elseif($donation->donor_name)
                                            <span class="fw-medium">{{ $donation->donor_name }}</span>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-user-secret me-1"></i>Anonymous
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="fw-bold text-success">₱{{ number_format($donation->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $donation->purpose == 'tithe' ? 'primary' : ($donation->purpose == 'offering' ? 'success' : 'info') }}">
                                        {{ ucfirst($donation->purpose) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $donation->reference_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $donation->payment_method == 'cash' ? 'success' : 'info' }}">
                                        {{ ucfirst($donation->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y') : 'Not set' }}</div>
                                        <small class="text-muted">{{ $donation->transaction_date ? $donation->transaction_date->format('h:i A') : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $donation->status == 'verified' ? 'success' : ($donation->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('treasurer.donations.show', $donation->id) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($donation->status == 'pending')
                                            <button class="btn btn-outline-success" 
                                                    onclick="verifyDonation({{ $donation->id }})" 
                                                    title="Verify">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="declineDonation({{ $donation->id }})" 
                                                    title="Decline">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <div>No donations found</div>
                                        <small>Try adjusting your filters or add a new donation.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Results Info -->
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <i class="fas fa-list me-1"></i>
                                Showing {{ $donations->firstItem() ?? 0 }} to {{ $donations->lastItem() ?? 0 }} of {{ $donations->total() }} donations
                            </span>
                            @if($donations->total() > 0)
                                <span class="badge bg-light text-dark">
                                    Page {{ $donations->currentPage() }} of {{ $donations->lastPage() }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Pagination Controls -->
                        <div class="d-flex align-items-center">
                            @if($donations->hasPages())
                                <!-- Previous Button -->
                                @if($donations->onFirstPage())
                                    <button class="btn btn-outline-secondary btn-sm me-2" disabled>
                                        <i class="fas fa-chevron-left me-1"></i>Previous
                                    </button>
                                @else
                                    <a href="{{ $donations->previousPageUrl() }}" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-chevron-left me-1"></i>Previous
                                    </a>
                                @endif
                                
                                <!-- Page Numbers -->
                                <div class="btn-group me-2" role="group">
                                    @php
                                        $start = max($donations->currentPage() - 2, 1);
                                        $end = min($start + 4, $donations->lastPage());
                                        $start = max($end - 4, 1);
                                    @endphp
                                    
                                    @if($start > 1)
                                        <a href="{{ $donations->url(1) }}" class="btn btn-outline-secondary btn-sm">1</a>
                                        @if($start > 2)
                                            <span class="btn btn-outline-secondary btn-sm disabled">...</span>
                                        @endif
                                    @endif
                                    
                                    @for($i = $start; $i <= $end; $i++)
                                        @if($i == $donations->currentPage())
                                            <button class="btn btn-primary btn-sm active">{{ $i }}</button>
                                        @else
                                            <a href="{{ $donations->url($i) }}" class="btn btn-outline-secondary btn-sm">{{ $i }}</a>
                                        @endif
                                    @endfor
                                    
                                    @if($end < $donations->lastPage())
                                        @if($end < $donations->lastPage() - 1)
                                            <span class="btn btn-outline-secondary btn-sm disabled">...</span>
                                        @endif
                                        <a href="{{ $donations->url($donations->lastPage()) }}" class="btn btn-outline-secondary btn-sm">{{ $donations->lastPage() }}</a>
                                    @endif
                                </div>
                                
                                <!-- Next Button -->
                                @if($donations->hasMorePages())
                                    <a href="{{ $donations->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">
                                        Next<i class="fas fa-chevron-right ms-1"></i>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled>
                                        Next<i class="fas fa-chevron-right ms-1"></i>
                                    </button>
                                @endif
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
    /* Enhanced Pagination Styles */
    .pagination-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    
    .pagination-card .card-body {
        padding: 1rem 1.5rem;
    }
    
    .btn-group .btn {
        border-radius: 6px;
        margin: 0 2px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover:not(.disabled):not(.active) {
        background-color: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-1px);
    }
    
    .btn-group .btn.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }
    
    .btn-group .btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .pagination-info i {
        color: #0d6efd;
    }
    
    .page-badge {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
    }
    
    /* Responsive Pagination */
    @media (max-width: 768px) {
        .pagination-card .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .pagination-card .d-flex > div {
            width: 100%;
            justify-content: center;
        }
        
        .btn-group {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn-group .btn {
            margin: 2px;
        }
    }
    
    /* Hover Effects */
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }
    
    .btn-outline-secondary:hover:not(.disabled) {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const status = document.getElementById('statusFilter');
        const date = document.getElementById('dateFilter');
        
        if (status) status.addEventListener('change', () => status.form.submit());
        if (date) date.addEventListener('change', () => date.form.submit());
    });

    function verifyDonation(donationId) {
        if (confirm('Are you sure you want to verify this donation?')) {
            fetch(`/treasurer/donations/${donationId}/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error verifying donation: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while verifying the donation.');
            });
        }
    }

    function declineDonation(donationId) {
        const reason = prompt('Please provide a reason for declining this donation:');
        if (reason && reason.trim() !== '') {
            fetch(`/treasurer/donations/${donationId}/decline`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error declining donation: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while declining the donation.');
            });
        }
    }
</script>
@endpush




