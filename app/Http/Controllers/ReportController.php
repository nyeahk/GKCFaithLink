<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Event;
use PDF;

class ReportController extends Controller
{
    /**
     * Get the appropriate layout based on user role
     *
     * @return string
     */
    private function getLayout()
    {
        $user = auth()->user();
        
        if ($user->role == 1) {
            return 'layouts.admin';
        } elseif ($user->role == 2) {
            return 'layouts.treasurer';
        }
        
        return 'layouts.app';
    }

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
            
        return view('reports.index', [
            'currentMonth' => $currentMonth, 
            'totalDonations' => $totalDonations, 
            'totalAmount' => $totalAmount,
            'layout' => $this->getLayout()
        ]);
    }
    
    public function weekly(Request $request)
{
    date_default_timezone_set('Asia/Manila');

    $startDate = $request->has('date') && !empty($request->date)
        ? Carbon::parse($request->date)->startOfWeek()
        : Carbon::now()->startOfWeek();
    $endDate = $startDate->copy()->endOfWeek();

    // Status filter
    $statusFilter = $request->input('status');

    // Build base query with date range
    $baseQuery = Donation::whereBetween('created_at', [$startDate, $endDate]);

    // Apply status filter if provided
    if ($statusFilter && $statusFilter !== '') {
        $baseQuery->where('status', $statusFilter);
    } else {
        // Default to approved/verified if no filter is applied
        $baseQuery->whereIn('status', ['approved', 'verified', 'completed']);
    }

    // Get total tithes for the week
    $totalTithes = (clone $baseQuery)
        ->where(function($query) {
            $query->where('purpose', 'tithe')
                  ->orWhere('purpose', 'like', '%tithe%')
                  ->orWhere('purpose', 'like', '%tithes%');
        })
        ->sum('amount');

    // Get total offerings for the week
    $totalOfferings = (clone $baseQuery)
        ->where(function($query) {
            $query->where('purpose', 'offering')
                  ->orWhere('purpose', 'like', '%offering%')
                  ->orWhere('purpose', 'like', '%offerings%');
        })
        ->whereRaw("(purpose NOT LIKE '%tithe%' AND purpose NOT LIKE '%mission%')")
        ->sum('amount');

    // Get total mission funds for the week
    $totalMissionFunds = (clone $baseQuery)
        ->where(function($query) {
            $query->where('purpose', 'mission')
                  ->orWhere('purpose', 'like', '%mission%');
        })
        ->sum('amount');

    // Get donations for the table (paginated)
    $donations = (clone $baseQuery)
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Prepare chart data
    $donationDays = [];
    $donationAmounts = [];
    $currentDay = $startDate->copy();
    while ($currentDay <= $endDate) {
        $dayTotal = (clone $baseQuery)->whereDate('created_at', $currentDay)->sum('amount');
        $donationDays[] = $currentDay->format('D');
        $donationAmounts[] = $dayTotal;
        $currentDay->addDay();
    }

    $totalAmount = $totalTithes + $totalOfferings + $totalMissionFunds;

    return view('reports.weekly', [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'totalTithes' => $totalTithes,
        'totalOfferings' => $totalOfferings,
        'totalMissionFunds' => $totalMissionFunds,
        'totalAmount' => $totalAmount,
        'donationDays' => $donationDays,
        'donationAmounts' => $donationAmounts,
        'donations' => $donations,
        'layout' => $this->getLayout()
    ]);
}
    
    public function monthly(Request $request)
    {
        // Set timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the start date from request or default to current month
        $startDate = $request->has('date') && !empty($request->date)
            ? Carbon::parse($request->date)->startOfMonth()
            : Carbon::now()->startOfMonth();

        // Calculate end date (end of month)
        $endDate = $startDate->copy()->endOfMonth();

        // Get status filter from request
        $statusFilter = $request->input('status');
        
        // Build base query with date range
        $baseQuery = Donation::whereBetween('created_at', [$startDate, $endDate]);
        
        // Apply status filter if provided
        if ($statusFilter && $statusFilter !== '') {
            $baseQuery->where('status', $statusFilter);
        } else {
            // Default to approved/verified if no filter is applied
            $baseQuery->whereIn('status', ['approved', 'verified']);
        }

        // Get total tithes for the month
        $totalTithes = (clone $baseQuery)
            ->where(function($query) {
                $query->where('purpose', 'tithe')
                      ->orWhere('purpose', 'like', '%tithe%')
                      ->orWhere('purpose', 'like', '%tithes%');
            })
            ->sum('amount');

        // Get total offerings for the month
        $totalOfferings = (clone $baseQuery)
            ->where(function($query) {
                $query->where('purpose', 'offering')
                      ->orWhere('purpose', 'like', '%offering%')
                      ->orWhere('purpose', 'like', '%offerings%');
            })
            ->whereRaw("(purpose NOT LIKE '%tithe%' AND purpose NOT LIKE '%mission%')")
            ->sum('amount');

        // Get total mission funds for the month
        $totalMissionFunds = (clone $baseQuery)
            ->where(function($query) {
                $query->where('purpose', 'mission')
                      ->orWhere('purpose', 'like', '%mission%');
            })
            ->sum('amount');

        // Get recent donations for the month
        $recentDonations = (clone $baseQuery)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
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
            
            $weekQuery = Donation::whereBetween('created_at', [$currentWeek, $weekEnd]);
            
            // Apply same status filter to weekly totals
            if ($statusFilter && $statusFilter !== '') {
                $weekQuery->where('status', $statusFilter);
            } else {
                $weekQuery->whereIn('status', ['approved', 'verified', 'completed']);
            }
            
            $weekTotal = $weekQuery->sum('amount');
            
            $donationWeeks[] = 'Week ' . ceil($currentWeek->day / 7);
            $donationAmounts[] = $weekTotal;
            
            $currentWeek->addWeek();
        }

        // Get total amount
        $totalAmount = $totalTithes + $totalOfferings + $totalMissionFunds;

        // Get all donations for the month - REMOVE status filter
        $donations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.monthly', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalTithes' => $totalTithes,
            'totalOfferings' => $totalOfferings,
            'totalMissionFunds' => $totalMissionFunds,
            'totalAmount' => $totalAmount,
            'newMembers' => 0, // Temporarily set to 0
            'recentDonations' => $recentDonations,
            'donationWeeks' => $donationWeeks,
            'donationAmounts' => $donationAmounts,
            'donations' => $donations,
            'layout' => $this->getLayout()
        ]);
    }

    /**
     * Download weekly report as PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadWeeklyReport(Request $request)
    {
        // Set timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the start date from request or default to current week
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfWeek();

        // Calculate end date (end of week)
        $endDate = $startDate->copy()->endOfWeek();

        // Get all donations for the week - Only include approved/verified donations
        $donations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'verified', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get totals with more specific conditions
        $totalTithes = $donations->filter(function($donation) {
            return strtolower($donation->purpose) === 'tithe' || 
                   str_contains(strtolower($donation->purpose), 'tithe');
        })->sum('amount');

        $totalOfferings = $donations->filter(function($donation) {
            return strtolower($donation->purpose) === 'offering' || 
                   str_contains(strtolower($donation->purpose), 'offering');
        })->sum('amount');

        $totalMissionFunds = $donations->filter(function($donation) {
            return strtolower($donation->purpose) === 'mission' || 
                   str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');

        $totalAmount = $donations->sum('amount');

        // Create PDF with UTF-8 encoding
        $pdf = \PDF::loadView('reports.pdf.weekly', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'donations' => $donations,
            'totalTithes' => $totalTithes,
            'totalOfferings' => $totalOfferings,
            'totalMissionFunds' => $totalMissionFunds,
            'totalAmount' => $totalAmount
        ]);
        
        // Set PDF options
        $pdf->setPaper('a4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'dejavu sans',
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        // Generate filename
        $filename = 'weekly_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }

    /**
     * Download monthly report as PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadMonthlyReport(Request $request)
    {
        // Set timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the start date from request or default to current month
        $startDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))
            : Carbon::now()->startOfMonth();

        // Calculate end date (end of month)
        $endDate = $startDate->copy()->endOfMonth();

        // Get all donations for the month - Only include approved/verified donations
        $donations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'verified', 'completed'])
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

        // Create PDF
        $pdf = \PDF::loadView('reports.pdf.monthly', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'donations' => $donations,
            'totalTithes' => $totalTithes,
            'totalOfferings' => $totalOfferings,
            'totalMissionFunds' => $totalMissionFunds,
            'totalAmount' => $totalAmount
        ]);

        // Generate filename
        $filename = 'monthly_report_' . $startDate->format('Y_m') . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }
}



















