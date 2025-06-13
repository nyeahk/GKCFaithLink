<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Donation;
use PDF;

class ReportsController extends Controller
{
    /**
     * Display the reports index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->format('F Y');
        
        // Get total donations for current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->count();
            
        $totalAmount = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');
            
        return view('treasurer.reports.index', compact('currentMonth', 'totalDonations', 'totalAmount'));
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
            ->take(10)
            ->get();

        // Prepare data for the donations chart by day (not week)
        $donationDays = [];
        $donationAmounts = [];
        $currentDay = $startDate->copy();
        while ($currentDay <= $endDate) {
            $dayTotal = Donation::whereDate('created_at', $currentDay)
                ->where('status', 'approved')
                ->sum('amount');
            $donationDays[] = $currentDay->format('M d');
            $donationAmounts[] = $dayTotal;
            $currentDay->addDay();
        }

        return view('treasurer.reports.monthly', compact(
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

        $pdf = PDF::loadView('treasurer.reports.pdf.donations', compact(
            'startDate',
            'endDate',
            'recentDonations',
            'totalTithes',
            'totalOfferings',
            'totalMissionFunds'
        ));

        return $pdf->download('monthly_donations_report_' . $startDate->format('Y-m') . '.pdf');
    }

    /**
     * Display the weekly report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            ->whereRaw("(purpose NOT LIKE '%tithe%' AND purpose NOT LIKE '%mission%')")
            ->sum('amount');

        // Get total mission funds for the week
        $totalMissionFunds = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->where(function($query) {
                $query->where('purpose', 'mission')
                      ->orWhere('purpose', 'like', '%mission%');
            })
            ->sum('amount');

        // Get recent donations for the week
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Prepare data for the donations chart by day
        $donationDays = [];
        $donationAmounts = [];
        
        // Get donations for each day of the week
        $currentDay = $startDate->copy();
        while ($currentDay <= $endDate) {
            $dayEnd = $currentDay->copy()->endOfDay();
            
            $dayTotal = Donation::whereBetween('created_at', [$currentDay, $dayEnd])
                ->where('status', 'approved')
                ->sum('amount');
            
            $donationDays[] = $currentDay->format('D');
            $donationAmounts[] = $dayTotal;
            
            $currentDay->addDay();
        }

        // Use the reports.weekly view instead of treasurer.reports.weekly
        return view('reports.weekly', compact(
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

    /**
     * Download the weekly report as PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadWeeklyReport(Request $request)
    {
        // Get the start date from request or default to current week
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfWeek();

        // Calculate end date (end of week)
        $endDate = $startDate->copy()->endOfWeek();

        // Get all donations for the week
        $donations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get totals with more specific conditions
        $totalTithes = $donations->filter(function($donation) {
            return strtolower($donation->purpose) === 'tithe' || 
                   str_contains(strtolower($donation->purpose), 'tithe');
        })->sum('amount');

        $totalOfferings = $donations->filter(function($donation) {
            return (strtolower($donation->purpose) === 'offering' || 
                   str_contains(strtolower($donation->purpose), 'offering')) &&
                   !str_contains(strtolower($donation->purpose), 'tithe') &&
                   !str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');

        $totalMissionFunds = $donations->filter(function($donation) {
            return strtolower($donation->purpose) === 'mission' || 
                   str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');

        $totalAmount = $donations->sum('amount');

        // Create PDF using the reports.pdf.weekly view
        $pdf = PDF::loadView('reports.pdf.weekly', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'donations' => $donations,
            'totalTithes' => $totalTithes,
            'totalOfferings' => $totalOfferings,
            'totalMissionFunds' => $totalMissionFunds,
            'totalAmount' => $totalAmount
        ]);

        // Generate filename
        $filename = 'weekly_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }
}





