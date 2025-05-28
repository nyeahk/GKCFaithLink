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
