@extends('layouts.admin')

@section('title', 'Monthly Report')

@section('content')
<div class="container-fluid">
    <!-- Clean Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-calendar-month me-2"></i>Monthly Report
            </h1>
            <p class="text-muted mb-0">{{ $startDate->format('F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="bi bi-calendar-range me-1"></i>
                {{ $startDate->format('F Y') }}
            </span>
            <a href="{{ route('admin.reports.monthly.download', ['date' => $startDate->format('Y-m-d')]) }}"
               class="btn btn-success">
                <i class="bi bi-download me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel me-2"></i>Filters
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.monthly') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="statusFilter" class="form-label">Filter by Status</label>
                    <select name="status" id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="dateFilter" class="form-label">Filter by Month</label>
                    <input type="month" name="date" id="dateFilter" class="form-control"
                           value="{{ request('date', $startDate->format('Y-m')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.reports.monthly') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Tithes
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalTithes ?? 0, 2) }}
                            </div>
                            <div class="text-xs text-muted">This Month</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Offerings
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalOfferings ?? 0, 2) }}
                            </div>
                            <div class="text-xs text-muted">This Month</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Mission Funds
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalMissionFunds ?? 0, 2) }}
                            </div>
                            <div class="text-xs text-muted">This Month</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Total Donations
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $recentDonations->count() }}
                            </div>
                            <div class="text-xs text-muted">This Month</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Average Donation
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ $recentDonations->count() > 0 ? number_format($recentDonations->avg('amount'), 2) : '0.00' }}
                            </div>
                            <div class="text-xs text-muted">Per Donation</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Largest Donation
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ $recentDonations->count() > 0 ? number_format($recentDonations->max('amount'), 2) : '0.00' }}
                            </div>
                            <div class="text-xs text-muted">Single Amount</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-secondary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-secondary text-uppercase mb-1">
                                Completed
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $recentDonations->where('status', 'completed')->count() }}
                            </div>
                            <div class="text-xs text-muted">Successful</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>Donations by Week
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Chart Options:</div>
                            <a class="dropdown-item" href="#" onclick="changeChartType('bar')">Bar Chart</a>
                            <a class="dropdown-item" href="#" onclick="changeChartType('line')">Line Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="donationsChart" style="height: 320px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>Monthly Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">Grand Total</h4>
                            <h2 class="text-success">₱{{ number_format(($totalTithes ?? 0) + ($totalOfferings ?? 0) + ($totalMissionFunds ?? 0), 2) }}</h2>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Category Breakdown</small>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="progress mb-1" style="height: 20px;">
                                    @php
                                        $total = ($totalTithes ?? 0) + ($totalOfferings ?? 0) + ($totalMissionFunds ?? 0);
                                        $tithePercent = $total > 0 ? (($totalTithes ?? 0) / $total) * 100 : 0;
                                        $offeringPercent = $total > 0 ? (($totalOfferings ?? 0) / $total) * 100 : 0;
                                        $missionPercent = $total > 0 ? (($totalMissionFunds ?? 0) / $total) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $tithePercent }}%" aria-valuenow="{{ $tithePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $offeringPercent }}%" aria-valuenow="{{ $offeringPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $missionPercent }}%" aria-valuenow="{{ $missionPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <small class="text-primary">■ Tithes: {{ number_format($tithePercent, 1) }}%</small>
                            </div>
                            <div class="col-12 mb-1">
                                <small class="text-success">■ Offerings: {{ number_format($offeringPercent, 1) }}%</small>
                            </div>
                            <div class="col-12 mb-1">
                                <small class="text-info">■ Missions: {{ number_format($missionPercent, 1) }}%</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check me-1"></i>
                                Report generated on {{ now()->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-table me-2"></i>Monthly Donations Details
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Donor</th>
                            <th>Purpose</th>
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
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($donation->purpose ?? 'General') }}</span>
                                </td>
                                <td class="fw-bold">₱{{ number_format($donation->amount, 2) }}</td>
                                <td>{{ ucfirst($donation->payment_method ?? 'N/A') }}</td>
                                <td>
                                    @if($donation->status == 'completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Completed
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Failed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox me-2"></i>No donations found for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles for admin monthly reports */
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .chart-area {
        position: relative;
        height: 320px;
        width: 100%;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .font-weight-bold {
        font-weight: 700 !important;
    }
    .animated--fade-in {
        animation: fadeIn 0.15s ease-in;
    }
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let donationsChart;

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('donationsChart').getContext('2d');

        // Create gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.8)');
        gradient.addColorStop(1, 'rgba(103, 138, 243, 0.1)');

        // Chart data
        const chartData = {
            labels: {!! json_encode($donationWeeks ?? []) !!},
            datasets: [{
                label: 'Weekly Donations (₱)',
                data: {!! json_encode($donationAmounts ?? []) !!},
                backgroundColor: gradient,
                borderColor: '#4e73df',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
                tension: 0.3,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true
            }]
        };

        // Chart options
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Amount: ₱' + new Intl.NumberFormat().format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: 'bold'
                        },
                        color: '#858796'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(200, 200, 200, 0.3)',
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#858796',
                        callback: function(value) {
                            return '₱' + new Intl.NumberFormat().format(value);
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        };

        // Create chart
        donationsChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    });

    // Function to change chart type
    function changeChartType(type) {
        if (donationsChart) {
            donationsChart.config.type = type;

            // Adjust styling based on chart type
            if (type === 'line') {
                donationsChart.data.datasets[0].backgroundColor = 'rgba(78, 115, 223, 0.1)';
                donationsChart.data.datasets[0].borderWidth = 3;
                donationsChart.data.datasets[0].pointRadius = 6;
                donationsChart.data.datasets[0].pointHoverRadius = 8;
            } else {
                const ctx = donationsChart.ctx;
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(78, 115, 223, 0.8)');
                gradient.addColorStop(1, 'rgba(78, 115, 223, 0.1)');
                donationsChart.data.datasets[0].backgroundColor = gradient;
                donationsChart.data.datasets[0].borderWidth = 2;
            }

            donationsChart.update();
        }
    }
</script>
@endpush