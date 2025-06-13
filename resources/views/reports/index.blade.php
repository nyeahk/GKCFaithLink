@extends($layout ?? 'layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Financial Reports</h1>
            <p class="mb-4">View and analyze donation statistics and financial reports.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Current Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $currentMonth }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Donations (This Month)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDonations }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Amount (This Month)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱{{ number_format($totalAmount, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Weekly Reports</h6>
                </div>
                <div class="card-body">
                    <p>View detailed weekly donation reports and statistics.</p>
                    <a href="{{ route('reports.weekly') }}" class="btn btn-primary btn-block">
                        <i class="bi bi-calendar-week me-2"></i> View Weekly Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Reports</h6>
                </div>
                <div class="card-body">
                    <p>View detailed monthly donation reports and statistics.</p>
                    <a href="{{ route('reports.monthly') }}" class="btn btn-primary btn-block">
                        <i class="bi bi-calendar-month me-2"></i> View Monthly Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
