<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the treasurer dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // Set timezone
            date_default_timezone_set('Asia/Manila');
            
            // Get current date
            $today = Carbon::now();
            
            // Format current month for display
            $currentMonth = $today->format('F');
            $currentYear = $today->format('Y');
            
            // Calculate previous and next month
            $lastMonth = $today->copy()->subMonth();
            $nextMonth = $today->copy()->addMonth();
            
            $prevMonth = $lastMonth->format('m');
            $prevYear = $lastMonth->format('Y');
            $nextMonth = $nextMonth->format('m');
            $nextYear = $nextMonth->format('Y');
            
            // Get donation statistics
            $totalDonations = Donation::count();
            $pendingDonations = Donation::where('status', 'pending')->count();
            $verifiedDonations = Donation::where('status', 'verified')->count();
            $declinedDonations = Donation::where('status', 'declined')->count();
            
            // Get counts for dashboard stats
            $membersCount = User::where('role', 3)->count();
            
            // Get total amount by purpose for the current month
            $currentMonthStart = $today->copy()->startOfMonth();
            $currentMonthEnd = $today->copy()->endOfMonth();
            
            // Get total amounts
            $totalAmount = Donation::where('status', 'verified')->sum('amount');
            $currentMonthAmount = Donation::where('status', 'verified')
                ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->sum('amount');
            
            // Get recent donations
            $recentDonations = Donation::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            return view('treasurer.dashboard', compact(
                'currentMonth',
                'currentYear',
                'prevMonth',
                'prevYear',
                'nextMonth',
                'nextYear',
                'totalDonations',
                'pendingDonations',
                'verifiedDonations',
                'declinedDonations',
                'totalAmount',
                'currentMonthAmount',
                'recentDonations',
                'membersCount'
            ));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in Treasurer Dashboard: ' . $e->getMessage());
            
            // Return a simple view with error message
            return view('treasurer.dashboard', [
                'error' => 'An error occurred while loading the dashboard. Please try again later.',
                'totalDonations' => 0,
                'pendingDonations' => 0,
                'verifiedDonations' => 0,
                'declinedDonations' => 0,
                'totalAmount' => 0,
                'currentMonthAmount' => 0,
                'currentMonth' => $today->format('F Y'),
                'recentDonations' => collect([])
            ]);
        }
    }
}

