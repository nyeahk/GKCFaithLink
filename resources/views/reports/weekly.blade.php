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

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel me-2"></i>Filter Report
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.weekly') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="date" class="form-label">Select Week</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="{{ request('date', now()->startOfWeek()->format('Y-m-d')) }}">
                    <small class="text-muted">Select any date within the week you want to view</small>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter me-1"></i>Apply Filter
                    </button>
                    <a href="{{ route('reports.weekly') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
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
                                    @if($donation->user)
                                        {{ $donation->user->name }}
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
                                            <i class="bi bi-check-circle me-1"></i>Completed
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    @elseif($donation->status == 'declined' || $donation->status == 'failed')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Failed
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





