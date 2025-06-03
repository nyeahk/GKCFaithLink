<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Donations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .header p {
            color: #7f8c8d;
            margin-top: 0;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .status-approved {
            color: green;
        }
        .status-pending {
            color: orange;
        }
        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Weekly Donations Report</h1>
        <p>Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Tithes:</span>
            <span>PHP {{ number_format($totalTithes, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Offerings:</span>
            <span>PHP {{ number_format($totalOfferings, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Mission Funds:</span>
            <span>PHP {{ number_format($totalMissionFunds, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Donations:</span>
            <span>PHP {{ number_format($totalTithes + $totalOfferings + $totalMissionFunds, 2) }}</span>
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
            @foreach($recentDonations as $donation)
                <tr>
                    <td>{{ $donation->created_at->format('M j, Y') }}</td>
                    <td>{{ $donation->donor_name }}</td>
                    <td>{{ $donation->purpose }}</td>
                    <td>PHP {{ number_format($donation->amount, 2) }}</td>
                    <td class="status-{{ strtolower($donation->status) }}">{{ ucfirst($donation->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
        <p>GKC FaithLink - Church Management System</p>
    </div>
</body>
</html>