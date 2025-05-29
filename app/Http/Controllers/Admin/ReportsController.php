<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class ReportsController extends Controller
{
    public function weekly(Request $request)
    {
        // Get the start date from request or default to current week
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfWeek();

        // Calculate end date (end of week)
        $endDate = $startDate->copy()->endOfWeek();

        // Get total tithes for the week
        $totalTithes = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'tithe')
                      ->orWhere('purpose', 'like', '%tithe%')
                      ->orWhere('purpose', 'like', '%tithes%');
            })
            ->sum('amount');

        // Get total offerings for the week
        $totalOfferings = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'offering')
                      ->orWhere('purpose', 'like', '%offering%')
                      ->orWhere('purpose', 'like', '%offerings%');
            })
            ->sum('amount');

        // Get total donations for the week
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');

        // Get donations by day
        $donationsByDay = [];
        for ($day = 0; $day < 7; $day++) {
            $date = $startDate->copy()->addDays($day);
            $donationsByDay[$date->format('Y-m-d')] = [
                'day' => $date->format('l'),
                'date' => $date->format('M d, Y'),
                'amount' => Donation::whereDate('created_at', $date)
                    ->where('status', 'approved')
                    ->sum('amount')
            ];
        }

        // Get recent donations
        $recentDonations = Donation::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.weekly', compact(
            'startDate',
            'endDate',
            'totalTithes',
            'totalOfferings',
            'totalDonations',
            'donationsByDay',
            'recentDonations'
        ));
    }

    public function downloadWeekly(Request $request, $date = null)
    {
        // Get the start date from parameter or default to current week
        $startDate = $date 
            ? Carbon::parse($date)
            : Carbon::now()->startOfWeek();

        // Calculate end date (end of week)
        $endDate = $startDate->copy()->endOfWeek();

        // Get total tithes for the week
        $totalTithes = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'tithe')
                      ->orWhere('purpose', 'like', '%tithe%')
                      ->orWhere('purpose', 'like', '%tithes%');
            })
            ->sum('amount');

        // Get total offerings for the week
        $totalOfferings = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'offering')
                      ->orWhere('purpose', 'like', '%offering%')
                      ->orWhere('purpose', 'like', '%offerings%');
            })
            ->sum('amount');

        // Get total donations for the week
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');

        // Get donations by day
        $donationsByDay = [];
        for ($day = 0; $day < 7; $day++) {
            $date = $startDate->copy()->addDays($day);
            $donationsByDay[$date->format('Y-m-d')] = [
                'day' => $date->format('l'),
                'date' => $date->format('M d, Y'),
                'amount' => Donation::whereDate('created_at', $date)
                    ->where('status', 'approved')
                    ->sum('amount')
            ];
        }

        // Get all donations for the week
        $donations = Donation::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('admin.reports.pdf.weekly', compact(
            'startDate',
            'endDate',
            'totalTithes',
            'totalOfferings',
            'totalDonations',
            'donationsByDay',
            'donations'
        ));

        return $pdf->download('weekly-report-' . $startDate->format('Y-m-d') . '.pdf');
    }

    public function monthly(Request $request)
    {
        // Get the start date from request or default to current month
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))->startOfMonth()
            : Carbon::now()->startOfMonth();

        // Calculate end date (end of month)
        $endDate = $startDate->copy()->endOfMonth();

        // Get total tithes for the month
        $totalTithes = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'tithe')
                      ->orWhere('purpose', 'like', '%tithe%')
                      ->orWhere('purpose', 'like', '%tithes%');
            })
            ->sum('amount');

        // Get total offerings for the month
        $totalOfferings = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'offering')
                      ->orWhere('purpose', 'like', '%offering%')
                      ->orWhere('purpose', 'like', '%offerings%');
            })
            ->sum('amount');

        // Get total donations for the month
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');

        // Get donations by week
        $donationsByWeek = [];
        $currentDate = $startDate->copy();
        $weekNumber = 1;

        while ($currentDate->lte($endDate)) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->endOfWeek()->min($endDate);

            $donationsByWeek[] = [
                'week' => 'Week ' . $weekNumber,
                'start_date' => $weekStart->format('M d'),
                'end_date' => $weekEnd->format('M d'),
                'amount' => Donation::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where('status', 'approved')
                    ->sum('amount')
            ];

            $currentDate = $weekEnd->copy()->addDay();
            $weekNumber++;
        }

        // Get recent donations
        $recentDonations = Donation::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.monthly', compact(
            'startDate',
            'endDate',
            'totalTithes',
            'totalOfferings',
            'totalDonations',
            'donationsByWeek',
            'recentDonations'
        ));
    }

    public function downloadMonthly(Request $request, $date = null)
    {
        // Implementation similar to downloadWeekly but for monthly reports
        // ...
        return response()->json(['message' => 'Monthly report download not implemented yet']);
    }

    public function annual(Request $request)
    {
        // Implementation for annual reports
        // ...
        return view('admin.reports.annual');
    }

    public function downloadAnnual(Request $request, $year = null)
    {
        // Implementation for downloading annual reports
        // ...
        return response()->json(['message' => 'Annual report download not implemented yet']);
    }

    public function custom(Request $request)
    {
        // Implementation for custom date range reports
        // ...
        return view('admin.reports.custom');
    }

    public function generateCustom(Request $request)
    {
        // Implementation for generating custom date range reports
        // ...
        return view('admin.reports.custom_results');
    }

    public function downloadCustom(Request $request)
    {
        // Implementation for downloading custom date range reports
        // ...
        return response()->json(['message' => 'Custom report download not implemented yet']);
    }
} 
