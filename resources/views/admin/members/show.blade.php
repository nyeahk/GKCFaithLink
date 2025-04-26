@extends('layouts.gkc')

@section('title', $user->name . ' - Member Profile')

@section('content')
    <div class="member-profile">
        <div class="profile-header">
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p class="email">{{ $user->email }}</p>
                <p class="join-date">Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-card">
                <h3>Total Donations</h3>
                <p class="stat-value">₱{{ number_format($user->donations->sum('amount'), 2) }}</p>
            </div>
            <div class="stat-card">
                <h3>Donation Count</h3>
                <p class="stat-value">{{ $user->donations->count() }}</p>
            </div>
            <div class="stat-card">
                <h3>Last Donation</h3>
                <p class="stat-value">
                    @if($user->donations->isNotEmpty())
                        {{ $user->donations->first()->created_at->format('M d, Y') }}
                    @else
                        No donations yet
                    @endif
                </p>
            </div>
        </div>

        <div class="donation-history">
            <h2>Donation History</h2>
            @if($donations->isNotEmpty())
                <div class="donations-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Admin Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                                <tr>
                                    <td>{{ $donation->transaction_date ? $donation->transaction_date->format('M d, Y h:i A') : 'Not set' }}</td>
                                    <td>₱{{ number_format($donation->amount, 2) }}</td>
                                    <td>{{ $donation->payment_method }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $donation->status }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $donation->admin_response ?? 'No response' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $donations->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-donate"></i>
                    <p>No donation history found</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .member-profile {
        padding: 2rem;
    }

    .profile-header {
        background: white;
        border-radius: 0.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .profile-info h1 {
        color: #1a365d;
        margin: 0 0 0.5rem;
        font-size: 1.875rem;
    }

    .profile-info .email {
        color: #4a5568;
        margin: 0 0 0.5rem;
    }

    .profile-info .join-date {
        color: #718096;
        margin: 0;
        font-size: 0.875rem;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
        color: #4a5568;
        margin: 0 0 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-card .stat-value {
        color: #1a365d;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .donation-history {
        background: white;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .donation-history h2 {
        color: #1a365d;
        margin: 0 0 1.5rem;
        font-size: 1.25rem;
    }

    .donations-table {
        overflow-x: auto;
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

    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #4a5568;
        font-size: 1.125rem;
        margin: 0;
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
</style>
@endpush 