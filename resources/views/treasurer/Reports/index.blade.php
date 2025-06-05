@extends('layouts.treasurer')

@section('title', 'Reports Dashboard')

@section('content')
<div class="reports-container">
    <div class="reports-header">
        <h1>Financial Reports</h1>
    </div>

    <div class="reports-summary">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="summary-info">
                <h3>Current Period</h3>
                <p class="summary-value">{{ $currentMonth }}</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-donate"></i>
            </div>
            <div class="summary-info">
                <h3>Total Donations</h3>
                <p class="summary-value">{{ $totalDonations }}</p>
                <p class="summary-label">This Month</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="summary-info">
                <h3>Total Amount</h3>
                <p class="summary-value">₱{{ number_format($totalAmount, 2) }}</p>
                <p class="summary-label">This Month</p>
            </div>
        </div>
    </div>

    <div class="reports-grid">
        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="report-info">
                <h3>Weekly Report</h3>
                <p>View and download weekly donation reports</p>
                <a href="{{ route('treasurer.reports.weekly') }}" class="btn btn-primary">View Report</a>
            </div>
        </div>

        <div class="report-card">
            <div class="report-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="report-info">
                <h3>Monthly Report</h3>
                <p>View and download monthly donation reports</p>
                <a href="{{ route('treasurer.reports.monthly') }}" class="btn btn-primary">View Report</a>
            </div>
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
        margin-bottom: 2rem;
    }

    .reports-summary {
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

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .report-card {
        background: var(--white);
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 2px 4px var(--shadow);
    }

    .report-icon {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-dark);
        font-size: 2rem;
    }

    .report-info {
        flex: 1;
    }

    .report-info h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        color: var(--text-dark);
    }

    .report-info p {
        margin: 0 0 1rem 0;
        color: var(--text-light);
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
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    @media (max-width: 768px) {
        .reports-summary,
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
