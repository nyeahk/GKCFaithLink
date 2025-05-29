@extends('layouts.gkc')

@section('title', 'Weekly Report')

@section('content')
<div class="reports-container">
    <div class="reports-header">
        <div class="header-content">
            <h1>Weekly Report</h1>
            <p class="subtitle">View and analyze weekly donation data</p>
        </div>
        <div class="header-actions">
            <div class="report-period">
                <span class="period-label">Period:</span>
                <span class="period-value">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</span>
            </div>
            <a href="{{ route('reports.weekly.download', ['date' => $startDate->format('Y-m-d')]) }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="report-summary">
        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="summary-info">
                <h3>Total Tithes</h3>
                <p class="summary-value">₱{{ number_format($totalTithes, 2) }}</p>
                <p class="summary-label">This Week</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-gift"></i>
            </div>
            <div class="summary-info">
                <h3>Total Offerings</h3>
                <p class="summary-value">₱{{ number_format($totalOfferings, 2) }}</p>
                <p class="summary-label">This Week</p>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="summary-info">
                <h3>Total Mission Funds</h3>
                <p class="summary-value">₱{{ number_format($totalMissionFunds, 2) }}</p>
                <p class="summary-label">This Week</p>
            </div>
        </div>
    </div>

    <div class="report-details">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="donations-chart">
                    <h2>Donations by Category (Stack)</h2>
                    <canvas id="donationsStackChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="donations-distribution">
                    <h2>Donations by Category (Doughnut)</h2>
                    <canvas id="donationsDistributionChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="donations-chart">
                    <h2>Donations by Category (Bar)</h2>
                    <canvas id="donationsBarChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="donations-chart">
                    <h2>Donations by Category (Column)</h2>
                    <canvas id="donationsColumnChart"></canvas>
                </div>
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-content h1 {
        color: #1a365d;
        margin: 0;
        font-size: 1.875rem;
    }

    .header-content .subtitle {
        color: #4a5568;
        margin: 0.5rem 0 0;
        font-size: 1rem;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .report-period {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f7fafc;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }

    .period-label {
        font-weight: 500;
        color: #4a5568;
    }

    .period-value {
        color: #2b6cb0;
        font-weight: 600;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #3182ce;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2c5282;
    }

    .btn i {
        font-size: 1rem;
    }

    .report-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ebf8ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2b6cb0;
        font-size: 1.5rem;
    }

    .summary-info h3 {
        margin: 0;
        font-size: 1rem;
        color: #4a5568;
    }

    .summary-value {
        margin: 0.25rem 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2b6cb0;
    }

    .summary-label {
        margin: 0;
        font-size: 0.875rem;
        color: #718096;
    }

    .report-details {
        margin-top: 2rem;
    }

    .donations-chart,
    .donations-distribution {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        height: 400px;
    }

    .donations-chart:hover,
    .donations-distribution:hover {
        transform: translateY(-5px);
    }

    .donations-chart h2,
    .donations-distribution h2 {
        margin: 0 0 1.5rem 0;
        color: #2d3748;
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define common data and options
        const labels = ['Tithes', 'Offerings', 'Mission Funds'];
        const data = [
            {{ $totalTithes }}, 
            {{ $totalOfferings }}, 
            {{ $totalMissionFunds }}
        ];
        
        // Calculate percentages for display
        const total = data.reduce((a, b) => a + b, 0);
        const percentages = data.map(value => Math.round((value / total) * 100));
        
        // Define vibrant colors with gradients
        const backgroundColors = [
            'rgba(54, 162, 235, 0.8)',   // Blue
            'rgba(75, 192, 150, 0.8)',   // Green
            'rgba(255, 159, 64, 0.8)'    // Orange
        ];
        
        const borderColors = [
            'rgb(54, 162, 235)',
            'rgb(75, 192, 150)',
            'rgb(255, 159, 64)'
        ];

        // Common chart options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const percentage = percentages[context.dataIndex];
                            return `${label}: ₱${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            }
        };

        // Stack Chart
        const stackCtx = document.getElementById('donationsStackChart').getContext('2d');
        new Chart(stackCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    stack: 'Stack 0',
                    borderRadius: 5
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Doughnut Chart
        const doughnutCtx = document.getElementById('donationsDistributionChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    cutout: '60%'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('donationsBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                ...commonOptions,
                indexAxis: 'y',
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Column Chart
        const columnCtx = document.getElementById('donationsColumnChart').getContext('2d');
        new Chart(columnCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush




