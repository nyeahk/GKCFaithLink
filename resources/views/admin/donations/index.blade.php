@extends('layouts.gkc')

@section('title', 'Donations')

@section('content')
    <div class="donations-container">
        <div class="donations-header">
            <h1>Donations</h1>
            <div class="header-actions">
                <a href="{{ route('admin.donations.manual-create') }}" class="btn btn-primary">
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
                                    <a href="{{ route('members.show', ['id' => $donation->user_id]) }}" class="donor-link">
                                        <i class="fas fa-user"></i>
                                        {{ $donation->user->name }}
                                    </a>
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
                                <a href="{{ route('admin.donations.show', $donation->id) }}" class="btn btn-action btn-view" title="View Donation">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.donations.edit', $donation->id) }}" class="btn btn-action btn-edit" title="Edit Donation">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($donation->status === 'pending')
                                    <form action="{{ route('admin.donations.approve', $donation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-action btn-approve" title="Approve Donation" onclick="return confirm('Are you sure you want to approve this donation?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-action btn-decline" title="Decline Donation" onclick="showDeclineModal({{ $donation->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <form action="{{ route('admin.donations.destroy', $donation->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" title="Delete Donation" onclick="return confirm('Are you sure you want to delete this donation?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <i class="fas fa-donate"></i>
                                    <p>No donations found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donations->hasPages())
            <div class="pagination">
                {{ $donations->links() }}
            </div>
        @endif
    </div>

    <!-- Decline Modal -->
    <div id="declineModal" class="modal">
        <div class="modal-content">
            <h2>Decline Donation</h2>
            <form id="declineForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reason">Reason for Decline</label>
                    <textarea id="reason" name="reason" class="form-input" required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeclineModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .donations-container {
        padding: 2rem;
    }

    .donations-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .donations-header h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .donations-header .subtitle {
        color: #4a5568;
        margin: 0;
        font-size: 1rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: #3182ce;
        color: white;
        border-radius: 0.375rem;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .donations-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f7fafc;
        color: #1a365d;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #4a5568;
    }

    .donor-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4a5568;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-declined {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .btn-approve {
        background-color: #dcfce7;
        color: #166534;
    }

    .btn-approve:hover {
        background-color: #bbf7d0;
    }

    .btn-decline {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-decline:hover {
        background-color: #fed7d7;
    }

    .btn-view {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }

    .btn-view:hover {
        background-color: #bee3f8;
    }

    .btn-edit {
        background-color: #fefcbf;
        color: #b7791f;
    }

    .btn-edit:hover {
        background-color: #faf089;
    }

    .btn-delete {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background-color: #fed7d7;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-content i {
        font-size: 3rem;
        color: #cbd5e0;
    }

    .empty-content p {
        color: #4a5568;
        font-size: 1.125rem;
    }

    .alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: #f0fff4;
        color: #2f855a;
        border: 1px solid #c6f6d5;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        color: #2b6cb0;
        background-color: white;
        border: 1px solid #e2e8f0;
    }

    .pagination .page-item.active .page-link {
        background-color: #2b6cb0;
        color: white;
        border-color: #2b6cb0;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        position: relative;
        background-color: white;
        margin: 10% auto;
        padding: 0;
        width: 90%;
        max-width: 500px;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        color: #1a365d;
        font-size: 1.25rem;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #4a5568;
        cursor: pointer;
        padding: 0.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
    }

    .btn-secondary {
        background-color: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background-color: #cbd5e0;
    }

    .btn-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .btn-danger:hover {
        background-color: #fed7d7;
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