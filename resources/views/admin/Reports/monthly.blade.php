@extends('layouts.admin')

@section('title', 'Monthly Report')

@section('content')
<div class="reports-container">
    <div class="reports-header">
        <h1>Monthly Report</h1>
        <div class="header-actions">
            <div class="report-period">
                <span class="period-label">Period:</span>
                <span class="period-value">{{ $startDate->format('F Y') }}</span>
            </div>
            <a href="{{ route('admin.reports.monthly.download', ['date' => $startDate->format('Y-m-d')]) }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="filter-container">
        <form method="GET" action="{{ route('admin.reports.monthly') }}" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="statusFilter">Filter by Status:</label>
                    <select name="status" id="statusFilter" class="filter-select">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="dateFilter">Filter by Date:</label>
                    <input type="month" name="date" id="dateFilter" value="{{ request('date', $startDate->format('Y-m')) }}" class="filter-input">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.reports.monthly') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="report-summary">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="summary-info">
                <h3>Total Tithes</h3>
                <p class="summary-value">₱{{ number_format($totalTithes ?? 0, 2) }}</p>
                <p class="summary-label">This Month</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-gift"></i>
            </div>
            <div class="summary-info">
                <h3>Total Offerings</h3>
                <p class="summary-value">₱{{ number_format($totalOfferings ?? 0, 2) }}</p>
                <p class="summary-label">This Month</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="summary-info">
                <h3>Total Mission Funds</h3>
                <p class="summary-value">₱{{ number_format($totalMissionFunds ?? 0, 2) }}</p>
                <p class="summary-label">This Month</p>
            </div>
        </div>
    </div>

    <div class="report-details">
        <div class="donations-chart">
            <h2>Donations by Week</h2>
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
                            <td colspan="5" class="text-center">No donations match the selected filters.</td>
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
        background-color: var(--background);
        border-radius: 8px;
    }

    .reports-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .report-period {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--white);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .period-label {
        font-weight: 500;
        color: var(--text-light);
    }

    .period-value {
        font-weight: 600;
        color: var(--primary-dark);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-outline-secondary {
        background-color: transparent;
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-outline-secondary:hover {
        background-color: var(--background-light);
    }

    .btn i {
        font-size: 1rem;
    }

    .filter-container {
        background-color: var(--white);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--shadow);
    }

    .filter-form {
        width: 100%;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text);
    }

    .filter-select,
    .filter-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 4px;
        background-color: var(--white);
        color: var(--text);
        font-size: 1rem;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .report-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
        transition: transform 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
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

    @media (max-width: 992px) {
        .report-details {
            grid-template-columns: 1fr;
        }
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
        font-size: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
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

    tbody tr:hover {
        background-color: var(--background-light);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
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

    @media (max-width: 768px) {
        .reports-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-actions {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('donationsChart').getContext('2d');
        
        // Create gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
        gradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($donationWeeks ?? []) !!},
                datasets: [{
                    label: 'Donations (₱)',
                    data: {!! json_encode($donationAmounts ?? []) !!},
                    backgroundColor: gradient,
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += '₱' + new Intl.NumberFormat().format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush