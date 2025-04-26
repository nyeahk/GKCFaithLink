<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donations Report - GKC FaithLink</title>
    <style>
        :root {
            --primary-dark: #205781;
            --accent-teal: #4F959D;
            --secondary-mint: #98D2C0;
            --background-light: #F6F8D5;
            --white: #ffffff;
            --shadow: rgba(0, 0, 0, 0.1);
        }
        @page {
            margin: 2.5cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: var(--primary-dark);
           
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--primary-dark);
            padding-bottom: 15px;
        }
        .header h1 {
            color: var(--primary-dark);
            font-size: 28pt;
            margin: 0;
        }
        .header p {
            color: var(--accent-teal);
            font-size: 14pt;
            margin: 5px 0 0;
        }
        .date-range {
            text-align: center;
            margin: 20px 0;
            color: var(--primary-dark);
            font-size: 12pt;
        }
        .summary {
            margin-bottom: 30px;
            background: var(--white);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px var(--shadow);
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 12px;
            background-color: var(--secondary-mint);
            border-radius: 6px;
        }
        .summary-label {
            font-weight: bold;
            color: var(--primary-dark);
        }
        .summary-value {
            font-weight: bold;
            color: var(--primary-dark);
        }
        .grand-total {
            background-color: var(--accent-teal);
            color: var(--white);
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .grand-total .summary-label,
        .grand-total .summary-value {
            color: var(--white);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12pt;
            background-color: var(--white);
            box-shadow: 0 2px 8px var(--shadow);
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--background-light);
            color: var(--primary-dark);
        }
        tr:nth-child(even) {
            background-color: var(--background-light);
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10pt;
            color: var(--primary-dark);
            border-top: 1px solid var(--accent-teal);
            padding-top: 15px;
        }
        .page-number {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 10pt;
            color: var(--primary-dark);
        }
        /* Status colors */
        .status-approved {
            color: #2ECC40; /* Green */
            font-weight: bold;
        }
        .status-pending {
            color: #FF851B; /* Orange */
            font-weight: bold;
        }
        .status-cancelled {
            color: #FF4136; /* Red */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GKC FaithLink</h1>
        <p>Donations Report</p>
    </div>

    <div class="date-range">
        <p>Period: {{ $startDate->format('F j, Y') }} - {{ $endDate->format('F j, Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Tithes:</span>
            <span class="summary-value">PHP {{ number_format($totalTithes, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Offerings:</span>
            <span class="summary-value">PHP {{ number_format($totalOfferings, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Mission Funds:</span>
            <span class="summary-value">PHP {{ number_format($totalMissionFunds, 2) }}</span>
        </div>
        <div class="summary-item grand-total">
            <span class="summary-label">Grand Total:</span>
            <span class="summary-value">PHP {{ number_format($totalTithes + $totalOfferings + $totalMissionFunds, 2) }}</span>
        </div>
    </div>

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
