@extends($layout ?? 'layouts.app')

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
            
            <a href="{{ route('reports.monthly.download', ['date' => $startDate->format('Y-m-d')]) }}"
               class="btn btn-success">
                <i class="bi bi-download me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel me-2"></i>Filter Report
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.monthly') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="date" class="form-label">Select Month</label>
                    <input type="month" class="form-control" id="date" name="date" 
                           value="{{ request('date', now()->format('Y-m')) }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter me-1"></i>Apply Filter
                    </button>
                    <a href="{{ route('reports.monthly') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Tithes
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalTithes, 2) }}
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Offerings
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalOfferings, 2) }}
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Mission Funds
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                ₱{{ number_format($totalMissionFunds, 2) }}
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Total Donations
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $donations->count() }}
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
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>Donations by Month
                    </h6>
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
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="donationsPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-primary"></i> Tithes
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Offerings
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-info"></i> Mission
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="bi bi-table me-2"></i>Recent Donations
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Donor</th>
                            <th>Purpose</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations as $donation)
                            <tr>
                                <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($donation->user)
                                        {{ $donation->user->name }}
                                    @elseif($donation->donor_name)
                                        {{ $donation->donor_name }}
                                    @else
                                        Anonymous
                                    @endif
                                </td>
                                <td>{{ ucfirst($donation->purpose) }}</td>
                                <td>₱{{ number_format($donation->amount, 2) }}</td>
                                <td>
                                    @if($donation->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($donation->status == 'verified' || $donation->status == 'approved')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($donation->status == 'declined')
                                        <span class="badge bg-danger">Declined</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No donations found for this period</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Bar chart for daily donations
        const ctx = document.getElementById('donationsChart').getContext('2d');
        
        // Create gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.8)');
        gradient.addColorStop(1, 'rgba(103, 138, 243, 0.1)');

        // Chart data
        const chartData = {
            labels: {!! json_encode($donationDays ?? []) !!},
            datasets: [{
                label: 'Daily Donations (₱)',
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
        const donationsChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });

        // Pie chart for donation breakdown
        const pieCtx = document.getElementById('donationsPieChart').getContext('2d');
        
        // Get values for pie chart
        const tithes = {{ $totalTithes ?? 0 }};
        const offerings = {{ $totalOfferings ?? 0 }};
        const missions = {{ $totalMissionFunds ?? 0 }};
        
        // Create pie chart
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tithes', 'Offerings', 'Mission Funds'],
                datasets: [{
                    data: [tithes, offerings, missions],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ₱${new Intl.NumberFormat().format(value)} (${percentage}%)`;
                            }
                        }
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 2
                    }
                }
            }
        });
    });
</script>
@endpush








