<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Weekly Financial Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Weekly Financial Report</h1>
        <p>{{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>
    </div>
    
    <div class="summary">
        <h2>Summary</h2>
        <div class="summary-item">
            <strong>Total Tithes:</strong> ₱{{ number_format($totalTithes, 2) }}
        </div>
        <div class="summary-item">
            <strong>Total Offerings:</strong> ₱{{ number_format($totalOfferings, 2) }}
        </div>
        <div class="summary-item">
            <strong>Total Mission Funds:</strong> ₱{{ number_format($totalMissionFunds, 2) }}
        </div>
        <div class="summary-item">
            <strong>Total Amount:</strong> ₱{{ number_format($totalAmount, 2) }}
        </div>
    </div>
    
    <h2>Donation Details</h2>
    <table>
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
            @forelse($donations as $donation)
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
                <td>{{ $donation->purpose }}</td>
                <td>₱{{ number_format($donation->amount, 2) }}</td>
                <td>{{ ucfirst($donation->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No donations found for this period</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y h:i A') }}</p>
    </div>
</body>
</html>
