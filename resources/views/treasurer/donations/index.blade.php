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
                                @if($donation->user && $donation->user->name)
                                    <span class="donor-link">
                                        <i class="fas fa-user"></i>
                                        {{ $donation->user->name }}
                                    </span>
                                @elseif($donation->donor_name)
                                    <span class="donor-link">
                                        <i class="fas fa-user"></i>
                                        {{ $donation->donor_name }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user"></i>
                                        Unknown Donor
                                    </span>
                                @endif
                            </td>
                            <td>₱{{ number_format($donation->amount, 2) }}</td>
                            <td>{{ ucfirst($donation->purpose) }}</td>
                            <td>{{ $donation->reference_number ?? 'N/A' }}</td>
                            <td>{{ $donation->payment_method }}</td>
                            <td>{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}</td>
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .donations-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .donations-table th, 
    .donations-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .donations-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .donations-table tr:hover {
        background-color: #f8f9fa;
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
</style>
@endpush

@push('scripts')
<script>
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




