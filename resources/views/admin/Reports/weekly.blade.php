@extends('layouts.gkc')

@section('title', 'Weekly Report')

@section('content')
<div class="reports-container">
    <div class="reports-header">
        <h1>Weekly Report</h1>
        <div class="report-period">
            <span class="period-label">Period:</span>
            <span class="period-value">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</span>
        </div>
    </div>

    <div class="report-summary">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-donate"></i>
            </div>
            <div class="summary-info">
                <h3>Total Donations</h3>
                <p class="summary-value">₱{{ number_format($totalDonations, 2) }}</p>
                <p class="summary-label">This Week</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="summary-info">
                <h3>New Members</h3>
                <p class="summary-value">{{ $newMembers }}</p>
                <p class="summary-label">This Week</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="summary-info">
                <h3>Upcoming Events</h3>
                <p class="summary-value">{{ $upcomingEvents }}</p>
                <p class="summary-label">Next 7 Days</p>
            </div>
        </div>
    </div>

    <div class="report-details">
        <div class="donations-chart">
            <h2>Donations by Day</h2>
            <canvas id="donationsChart"></canvas>
        </div>

        <div class="donations-table">
            <h2>Recent Donations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDonations as $donation)
                        <tr>
                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                            <td>{{ $donation->donor_name }}</td>
                            <td>₱{{ number_format($donation->amount, 2) }}</td>
                            <td>{{ ucfirst($donation->payment_method) }}</td>
                            <td>
                                <span class="status-badge status-{{ $donation->status }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No donations this week.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .reports-container {
        padding: 2rem;
    }

    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .report-period {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .period-label {
        font-weight: 500;
        color: var(--text-light);
    }

    .period-value {
        color: var(--primary-dark);
    }

    .report-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 4px var(--shadow);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-dark);
        font-size: 1.5rem;
    }

    .summary-info h3 {
        margin: 0;
        font-size: 1rem;
        color: var(--text-light);
    }

    .summary-value {
        margin: 0.25rem 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-dark);
    }

    .summary-label {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-light);
    }

    .report-details {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .donations-chart,
    .donations-table {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px var(--shadow);
    }

    .donations-chart h2,
    .donations-table h2 {
        margin: 0 0 1.5rem 0;
        color: var(--primary-dark);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    th {
        background-color: var(--background-light);
        font-weight: 600;
        color: var(--text-light);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-completed {
        background-color: #28a745;
        color: var(--white);
    }

    .status-failed {
        background-color: #dc3545;
        color: var(--white);
    }

    .text-center {
        text-align: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('donationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($donationDays) !!},
                datasets: [{
                    label: 'Donations',
                    data: {!! json_encode($donationAmounts) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush 