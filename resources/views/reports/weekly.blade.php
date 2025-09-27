@extends($layout ?? 'layouts.app')

@section('title', 'Weekly Report')

@section('content')
<div class="container-fluid">
    <!-- Clean Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-calendar-week me-2"></i>Weekly Report
            </h1>
            <p class="text-muted mb-0">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="bi bi-calendar-range me-1"></i>
                {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
            </span>
            
            @if(auth()->user()->role_id == 1)
                <a href="{{ route('admin.reports.weekly.download', ['date' => $startDate->format('Y-m-d')]) }}"
                   class="btn btn-success">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
            @elseif(auth()->user()->role_id == 2)
                <a href="{{ route('treasurer.reports.weekly.download', ['date' => $startDate->format('Y-m-d')]) }}"
                   class="btn btn-success">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
            @else
                <a href="{{ route('reports.weekly.download', ['date' => $startDate->format('Y-m-d')]) }}"
                   class="btn btn-success">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
            @endif
        </div>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-funnel me-2 text-primary"></i>
                <h6 class="mb-0 fw-semibold">Filter Weekly Report</h6>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('reports.weekly') }}" id="weeklyFilterForm">
                <div class="row g-4">
                    <!-- Status Filter -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-floating">
                            <select name="status" id="statusFilter" class="form-select" onchange="document.getElementById('weeklyFilterForm').submit();" style="border-radius: 10px;">
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
                    
                    <!-- Week Filter -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-floating">
                            <input type="week" name="date" id="dateFilter" class="form-control"
                                   value="{{ request('date', \Carbon\Carbon::now()->format('Y-\WW')) }}"
                                   onchange="document.getElementById('weeklyFilterForm').submit();"
                                   style="border-radius: 10px;">
                            <label for="dateFilter">
                                <i class="bi bi-calendar-week me-1"></i>Select Week
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
                                <a href="{{ route('reports.weekly') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
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
                                <i class="bi bi-calendar-week me-1"></i>Week: {{ request('date') }}
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
        <div class="col-xl-4 col-md-6 mb-4">
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
                            <div class="text-xs text-muted">This Week</div>
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
                            <div class="text-xs text-muted">This Week</div>
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
                                ₱{{ number_format($totalMissionFunds, 2) }}
                            </div>
                            <div class="text-xs text-muted">This Week</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-globe fa-2x text-gray-300"></i>
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
                        <i class="bi bi-bar-chart me-2"></i>Donations by Day
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="donationsChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Summary -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-calculator me-2"></i>Weekly Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">Grand Total</h4>
                            <h2 class="text-success">₱{{ number_format($totalTithes + $totalOfferings + $totalMissionFunds, 2) }}</h2>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Breakdown</small>
                            </div>
                            <div class="col-12 mb-1">
                                <span class="badge bg-primary">Tithes: ₱{{ number_format($totalTithes, 2) }}</span>
                            </div>
                            <div class="col-12 mb-1">
                                <span class="badge bg-success">Offerings: ₱{{ number_format($totalOfferings, 2) }}</span>
                            </div>
                            <div class="col-12 mb-1">
                                <span class="badge bg-info">Missions: ₱{{ number_format($totalMissionFunds, 2) }}</span>
                            </div>
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
                <i class="bi bi-table me-2"></i>Recent Donations
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
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($donation->purpose) }}</span>
                                </td>
                                <td class="fw-bold">₱{{ number_format($donation->amount, 2) }}</td>
                                <td>{{ ucfirst($donation->payment_method ?? 'N/A') }}</td>
                                <td>
                                    @if($donation->status == 'completed' || $donation->status == 'approved' || $donation->status == 'verified')
                                        <span class="badge bg-success">
                                            <i class=""></i>Approved
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge bg-warning">
                                            <i class=""></i>Pending
                                        </span>
                                    @elseif($donation->status == 'declined' || $donation->status == 'declined')
                                        <span class="badge bg-danger">
                                            <i class=""></i>Declined
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox me-2"></i>No donations found for this week.
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
    /* Custom styles for admin reports */
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .chart-area {
        position: relative;
        height: 300px;
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
                document.getElementById('weeklyFilterForm').submit();
            });
        }
        
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                document.getElementById('weeklyFilterForm').submit();
            });
        }
        
        // Add loading state to filter button
        const filterButton = document.querySelector('.filter-button');
        if (filterButton) {
            filterButton.addEventListener('click', function() {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Filtering...';
            });
        }

        const ctx = document.getElementById('donationsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($donationDays) !!},
                datasets: [{
                    label: 'Daily Donations (₱)',
                    data: {!! json_encode($donationAmounts) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush