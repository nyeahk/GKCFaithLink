@extends('layouts.treasurer')

@section('title', 'Donations')

@section('content')
    <div class="donations-container">
        <div class="donations-header">
            <h1>Donations</h1>
            <div class="header-actions">
                <a href="{{ route('treasurer.donations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Manual Donation
                </a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('treasurer.donations.index') }}" id="treasurerFilterForm" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="verified" {{ request('status')=='verified' ? 'selected' : '' }}>Verified</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                            <option value="declined" {{ request('status')=='declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dateFilter" class="form-label">Month</label>
                        <input type="month" name="date" id="dateFilter" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary mt-4"><i class="bi bi-funnel me-1"></i>Apply</button>
                        @if(request()->anyFilled(['status','date']))
                            <a href="{{ route('treasurer.donations.index') }}" class="btn btn-outline-secondary mt-4"><i class="bi bi-arrow-clockwise me-1"></i>Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="donations-table">
            <table>
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Reference Number</th>
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
                                    <span class="text-muted truncate" title="Anonymous">
                                        <i class="fas fa-user-secret"></i>
                                        Anonymous
                                    </span>
                                @else
                                    @if($donation->user)
                                        <span class="donor-link truncate" title="{{ $donation->user->getFullNameAttribute() }}">
                                            <i class="fas fa-user"></i>
                                            {{ $donation->user->getFullNameAttribute() }}
                                        </span>
                                    @elseif($donation->donor_name)
                                        <span class="donor-link truncate" title="{{ $donation->donor_name }}">
                                            <i class="fas fa-user"></i>
                                            {{ $donation->donor_name }}
                                        </span>
                                    @else
                                        <span class="text-muted truncate" title="Anonymous">
                                            <i class="fas fa-user-secret"></i>
                                            Anonymous
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="amount-cell">₱{{ number_format($donation->amount, 2) }}</td>
                            <td>
                                <span class="tag tag-secondary">{{ ucfirst($donation->purpose) }}</span>
                            </td>
                            <td>
                                <span class="truncate" title="{{ $donation->reference_number ?? 'N/A' }}">{{ $donation->reference_number ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="tag tag-info">{{ ucfirst($donation->payment_method) }}</span>
                            </td>
                            <td class="nowrap">{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}</td>
                            <td>
                                <span class="status-badge status-{{ $donation->status }}">
                                    <i class="fas fa-circle"></i>
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('treasurer.donations.show', $donation->id) }}" class="btn btn-action btn-view" title="View Donation">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No donations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container">
            {{ $donations->links() }}
        </div>
    </div>
@endsection

@push('styles')
<style>
    .donations-container {
        padding: 20px;
    }
    
    .donations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .donations-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
        overflow-y: hidden;
    }
    
    .donations-table table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        min-width: 980px;
    }
    
    .donations-table th, 
    .donations-table td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .donations-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .donations-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
    }
    
    .donor-link {
        color: #3490dc;
        font-weight: 500;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-badge i {
        font-size: 0.7rem;
        margin-right: 5px;
    }
    
    .status-pending {
        background-color: #fff8e1;
        color: #f6c23e;
    }
    
    .status-verified {
        background-color: #e8f5e9;
        color: #1cc88a;
    }
    
    .status-declined {
        background-color: #ffebee;
        color: #e74a3b;
    }
    
    .btn-action {
        padding: 5px 10px;
        border-radius: 4px;
        margin-right: 5px;
        color: white;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background-color: #36b9cc;
    }
    
    .btn-edit {
        background-color: #4e73df;
    }
    
    .btn-approve {
        background-color: #1cc88a;
    }
    
    .btn-decline {
        background-color: #e74a3b;
    }
    
    .btn-delete {
        background-color: #e74a3b;
    }
    
    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: #1cc88a;
        border: 1px solid #c8e6c9;
    }
    
    .alert i {
        margin-right: 8px;
    }

    /* New styles for tags */
    .tag {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1;
        max-width: 100%;
    }

    .tag-primary {
        background-color: #e0e0e0;
        color: #333;
    }

    .tag-secondary {
        background-color: #f1f3f5;
        color: #6c757d;
    }

    .tag-info {
        background-color: #e8f7fb;
        color: #36b9cc;
    }

    .tag-success {
        background-color: #e8f5e9;
        color: #1cc88a;
    }

    .tag-warning {
        background-color: #fffbe6;
        color: #f6c23e;
    }

    .tag-danger {
        background-color: #ffebee;
        color: #e74a3b;
    }

    .amount-cell {
        font-weight: 700;
        color: #1cc88a;
        white-space: nowrap;
    }

    /* Column sizing (hints) */
    .donations-table thead th:nth-child(1) { width: 200px; }
    .donations-table thead th:nth-child(2) { width: 120px; }
    .donations-table thead th:nth-child(3) { width: 140px; }
    .donations-table thead th:nth-child(4) { width: 180px; }
    .donations-table thead th:nth-child(5) { width: 140px; }
    .donations-table thead th:nth-child(6) { width: 170px; }
    .donations-table thead th:nth-child(7) { width: 120px; }
    .donations-table thead th:nth-child(8) { width: 120px; }

    .truncate { display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom; }

    .nowrap { white-space: nowrap; }
    .cell-donor { max-width: 220px; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('treasurerFilterForm');
        const status = document.getElementById('statusFilter');
        const date = document.getElementById('dateFilter');
        if (status) status.addEventListener('change', () => form.submit());
        if (date) date.addEventListener('change', () => form.submit());
    });

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
</script>
@endpush




