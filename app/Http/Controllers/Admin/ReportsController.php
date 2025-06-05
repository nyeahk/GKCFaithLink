<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Event;
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
                      ->orWhere('purpose', 'like', '%love offering%')
                      ->orWhere('purpose', 'like', '%special offering%');
            })
            ->where(function($query) {
                $query->where('purpose', 'not like', '%tithe%')
                      ->where('purpose', 'not like', '%mission%');
            })
            ->sum('amount');

        // Get total mission funds for the week
        $totalMissionFunds = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'mission')
                      ->orWhere('purpose', 'like', '%mission%')
                      ->orWhere('purpose', 'like', '%missions%')
                      ->orWhere('purpose', 'like', '%missionary%');
            })
            ->sum('amount');

        // Get recent donations for the week
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Prepare data for the donations chart
        $donationDays = [];
        $donationAmounts = [];
        
        // Get donations for each day of the week
        $currentDay = $startDate->copy();
        while ($currentDay <= $endDate) {
            $dayTotal = Donation::whereDate('created_at', $currentDay)
                ->sum('amount');
            
            $donationDays[] = $currentDay->format('D');
            $donationAmounts[] = $dayTotal;
            
            $currentDay->addDay();
        }

        return view('admin.reports.weekly', compact(
            'startDate',
            'endDate',
            'totalTithes',
            'totalOfferings',
            'totalMissionFunds',
            'recentDonations',
            'donationDays',
            'donationAmounts'
        ));
    }

    public function monthly(Request $request)
    {
        // Get the start date from request or default to current month
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
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
                      ->orWhere('purpose', 'like', '%love offering%')
                      ->orWhere('purpose', 'like', '%special offering%');
            })
            ->where(function($query) {
                $query->where('purpose', 'not like', '%tithe%')
                      ->where('purpose', 'not like', '%mission%');
            })
            ->sum('amount');

        // Get total mission funds for the month
        $totalMissionFunds = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'mission')
                      ->orWhere('purpose', 'like', '%mission%')
                      ->orWhere('purpose', 'like', '%missions%')
                      ->orWhere('purpose', 'like', '%missionary%');
            })
            ->sum('amount');

        // Get recent donations for the month
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Prepare data for the donations chart by week
        $donationWeeks = [];
        $donationAmounts = [];
        
        // Get donations for each week of the month
        $currentWeek = $startDate->copy();
        while ($currentWeek <= $endDate) {
            $weekEnd = $currentWeek->copy()->endOfWeek();
            if ($weekEnd > $endDate) {
                $weekEnd = $endDate;
            }
            
            $weekTotal = Donation::whereBetween('created_at', [$currentWeek, $weekEnd])
                ->sum('amount');
            
            $donationWeeks[] = 'Week ' . ceil($currentWeek->day / 7);
            $donationAmounts[] = $weekTotal;
            
            $currentWeek->addWeek();
        }

        return view('admin.reports.monthly', compact(
            'startDate',
            'endDate',
            'totalTithes',
            'totalOfferings',
            'totalMissionFunds',
            'recentDonations',
            'donationWeeks',
            'donationAmounts'
        ));
    }

    public function downloadWeeklyReport(Request $request)
    {
        // Get the start date from request or default to current week
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfWeek();

        // Calculate end date (end of week)
        $endDate = $startDate->copy()->endOfWeek();

        // Get all donations for the week
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get totals with more specific conditions
        $totalTithes = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'tithe' || 
                   str_contains(strtolower($donation->purpose), 'tithe');
        })->sum('amount');

        $totalOfferings = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'offering' || 
                   str_contains(strtolower($donation->purpose), 'offering');
        })->sum('amount');

        $totalMissionFunds = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'mission' || 
                   str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');

        $pdf = PDF::loadView('admin.reports.pdf.donations', compact(
            'startDate',
            'endDate',
            'recentDonations',
            'totalTithes',
            'totalOfferings',
            'totalMissionFunds'
        ));

        return $pdf->download('weekly_donations_report_' . $startDate->format('Y-m-d') . '.pdf');
    }

    public function downloadMonthlyReport(Request $request)
    {
        // Get the start date from request or default to current month
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfMonth();

        // Calculate end date (end of month)
        $endDate = $startDate->copy()->endOfMonth();

        // Get all donations for the month
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get totals with more specific conditions
        $totalTithes = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'tithe' || 
                   str_contains(strtolower($donation->purpose), 'tithe');
        })->sum('amount');

        $totalOfferings = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'offering' || 
                   str_contains(strtolower($donation->purpose), 'offering');
        })->sum('amount');

        $totalMissionFunds = $recentDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'mission' || 
                   str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');

        $pdf = PDF::loadView('admin.reports.pdf.donations', compact(
            'startDate',
            'endDate',
            'recentDonations',
            'totalTithes',
            'totalOfferings',
            'totalMissionFunds'
        ));

        return $pdf->download('monthly_donations_report_' . $startDate->format('Y-m') . '.pdf');
    }
} 