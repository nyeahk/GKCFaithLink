<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Event;

class ReportController extends Controller
{
    public function weekly()
    {
        // Set timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the current date
        $now = Carbon::now();
        
        // Calculate the start and end of the current week
        $startDate = $now->copy()->startOfWeek();
        $endDate = $now->copy()->endOfWeek();
        
        // Get total donations for the week
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        
        // Get new members for the week
        $newMembers = Member::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Get upcoming events in the next 7 days
        $upcomingEvents = Event::whereBetween('start_date', [$now, $now->copy()->addDays(7)])
            ->count();
        
        // Get recent donations for the week
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(10)
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
            'totalDonations',
            'newMembers',
            'upcomingEvents',
            'recentDonations',
            'donationDays',
            'donationAmounts'
        ));
    }
    
    public function monthly()
    {
        // Set timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the current date
        $now = Carbon::now();
        
        // Calculate the start and end of the current month
        $startDate = $now->copy()->startOfMonth();
        $endDate = $now->copy()->endOfMonth();
        
        // Get total donations for the month
        $totalDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        
        // Get new members for the month
        $newMembers = Member::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Get upcoming events in the next 30 days
        $upcomingEvents = Event::whereBetween('start_date', [$now, $now->copy()->addDays(30)])
            ->count();
        
        // Get recent donations for the month
        $recentDonations = Donation::whereBetween('created_at', [$startDate, $endDate])
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
            
            $weekTotal = Donation::whereBetween('created_at', [$currentWeek, $weekEnd])
                ->sum('amount');
            
            $donationWeeks[] = 'Week ' . ceil($currentWeek->day / 7);
            $donationAmounts[] = $weekTotal;
            
            $currentWeek->addWeek();
        }
        
        return view('admin.reports.monthly', compact(
            'startDate',
            'endDate',
            'totalDonations',
            'newMembers',
            'upcomingEvents',
            'recentDonations',
            'donationWeeks',
            'donationAmounts'
        ));
    }
}
