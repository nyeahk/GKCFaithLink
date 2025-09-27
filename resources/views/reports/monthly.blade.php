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

    <!-- Enhanced Filter Section -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-funnel me-2 text-primary"></i>
                <h6 class="mb-0 fw-semibold">Filter Monthly Report</h6>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('reports.monthly') }}" id="monthlyFilterForm">
                <div class="row g-4">
                    <!-- Status Filter -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-floating">
                            <select name="status" id="statusFilter" class="form-select" onchange="document.getElementById('monthlyFilterForm').submit();" style="border-radius: 10px;">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    <i class="bi bi-clock me-1"></i>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    <i class="bi bi-check-circle me-1"></i>Approved
                                </option>
                                <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>
                                    <i class="bi bi-x-circle me-1"></i>Declined
                                </option>
                            </select>
                            <label for="statusFilter">
                                <i class=""></i>Filter by Status
                            </label>
                        </div>
                    </div>
                    
                    <!-- Month Filter -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-floating">
                            <input type="month" name="date" id="dateFilter" class="form-control"
                                   value="{{ request('date', $startDate->format('Y-m')) }}"
                                   onchange="document.getElementById('monthlyFilterForm').submit();"
                                   style="border-radius: 10px;">
                            <label for="dateFilter">
                                <i class="bi bi-calendar-month me-1"></i>Select Month
                            </label>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex flex-column gap-2 h-100">
                            <button type="submit" class="btn btn-primary filter-button" style="border-radius: 10px; height: 58px;">
                                <i class="bi bi-funnel-fill me-1"></i>Apply Filters
                            </button>
                            @if(request()->anyFilled(['status', 'date']))
                                <a href="{{ route('reports.monthly') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Clear All
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters Display -->
                @if(request()->anyFilled(['status', 'date']))
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="text-muted small me-2">Active filters:</span>
                        @if(request('status'))
                            <span class="badge bg-{{ request('status') == 'approved' || request('status') == 'verified' ? 'success' : (request('status') == 'declined' ? 'danger' : 'warning') }}">
                                <i class="bi bi-toggle-{{ request('status') == 'approved' || request('status') == 'verified' ? 'on' : (request('status') == 'declined' ? 'off' : 'on') }} me-1"></i>
                                Status: {{ ucfirst(request('status')) }}
                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                        @if(request('date'))
                            <span class="badge bg-info">
                                <i class="bi bi-calendar-month me-1"></i>Month: {{ \Carbon\Carbon::createFromFormat('Y-m', request('date'))->format('F Y') }}
                                <a href="{{ request()->fullUrlWithQuery(['date' => null]) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
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
                        <i class="bi bi-bar-chart me-2"></i>Donations by Week
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
                    <div class="mt-4 text-center small monthly-breakdown-container">
                        <span class="monthly-breakdown-label tithes" style="color: #000 !important;">
                            <i class="fas fa-circle me-1" style="color: #2e59d9 !important;"></i> <span style="color: #000 !important;">Tithes</span>
                        </span>
                        <span class="monthly-breakdown-label offerings" style="color: #000 !important;">
                            <i class="fas fa-circle me-1" style="color: #17a673 !important;"></i> <span style="color: #000 !important;">Offerings</span>
                        </span>
                        <span class="monthly-breakdown-label mission" style="color: #000 !important;">
                            <i class="fas fa-circle me-1" style="color: #2c9faf !important;"></i> <span style="color: #000 !important;">Mission</span>
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
                                    @if($donation->anonymous)
                                        Anonymous
                                    @elseif($donation->user)
                                        {{ $donation->user->getFullNameAttribute() }}
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
                                        <span class="badge bg-success">Approved</span>
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

    /* Enhanced Filter Styles */
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .badge {
        border-radius: 6px;
        font-weight: 500;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .filter-button {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border: none;
    }

    .filter-button:hover {
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
        transform: translateY(-1px);
    }

    /* Styles for monthly breakdown labels */
    .monthly-breakdown-label {
        background-color: transparent !important;
        border: none !important;
        color: #000 !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        display: inline-flex !important;
        align-items: center !important;
        padding: 3px 8px !important;
        margin-right: 10px !important;
    }

    /* Container for labels */
    .monthly-breakdown-container {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 10px !important;
    }

    /* Specific colors for each label's circle */
    .monthly-breakdown-label.tithes i {
        color: #2e59d9 !important; /* Blue for Tithes */
    }
    .monthly-breakdown-label.offerings i {
        color: #17a673 !important; /* Green for Offerings */
    }
    .monthly-breakdown-label.mission i {
        color: #2c9faf !important; /* Teal for Mission */
    }

    /* Ensure text is black */
    .monthly-breakdown-label span {
        color: #000 !important;
        font-weight: 700 !important;
    }

    /* Override any other color settings */
    .monthly-breakdown-label.tithes span,
    .monthly-breakdown-label.offerings span,
    .monthly-breakdown-label.mission span {
        color: #000 !important;
    }

    /* Additional override for text color */
    .monthly-breakdown-label.tithes,
    .monthly-breakdown-label.offerings,
    .monthly-breakdown-label.mission {
        color: #000 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                document.getElementById('monthlyFilterForm').submit();
            });
        }
        
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                document.getElementById('monthlyFilterForm').submit();
            });
        }
        
        // Add loading state to filter button
        const filterButton = document.querySelector('.filter-button');
        if (filterButton) {
            filterButton.addEventListener('click', function() {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Filtering...';
            });
        }

        // Bar chart for weekly donations
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
                    backgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 2
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
                        backgroundColor: "rgba(255, 255, 255, 0.9)",
                        titleColor: "#000",
                        bodyColor: "#000",
                        borderColor: '#2e59d9',
                        borderWidth: 2,
                        padding: 15,
                        displayColors: true,
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
                        borderWidth: 2,
                        borderColor: '#fff'
                    }
                }
            }
        });
    });
</script>
@endpush








