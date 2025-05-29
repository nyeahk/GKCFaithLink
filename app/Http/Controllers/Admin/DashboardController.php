<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Remove any role middleware here to test if that's the issue
    }

    public function index(Request $request)
    {
        // Get timestamp from request or use current time
        $timestamp = $request->input('timestamp', now()->timestamp);
        $currentDate = Carbon::createFromTimestamp($timestamp);
        
        // Calculate previous and next month timestamps
        $lastMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        $lastMonthTimestamp = $lastMonth->timestamp;
        $nextMonthTimestamp = $nextMonth->timestamp;
        $todayTimestamp = Carbon::now()->timestamp;
        
        // Generate calendar data
        $calendar = $this->generateCalendar($currentDate);
        $daysInAWeek = 7;
        
        // Get counts for dashboard stats (if models exist)
        $announcementsCount = class_exists('App\Models\Announcement') ? 
            \App\Models\Announcement::count() : 0;
        
        $eventsCount = class_exists('App\Models\Event') ? 
            \App\Models\Event::where('start_date', '>=', Carbon::now())->count() : 0;
        
        $membersCount = class_exists('App\Models\Member') ? 
            \App\Models\Member::count() : 0;
        
        return view('admin.dashboard', compact(
            'currentDate', 
            'lastMonthTimestamp', 
            'nextMonthTimestamp', 
            'todayTimestamp',
            'calendar',
            'daysInAWeek',
            'announcementsCount',
            'eventsCount',
            'membersCount'
        ));
    }
    
    /**
     * Generate calendar data for the given month
     */
    private function generateCalendar($date)
    {
        // Get the first day of the month
        $firstDayOfMonth = $date->copy()->startOfMonth();
        
        // Get the last day of the month
        $lastDayOfMonth = $date->copy()->endOfMonth();
        
        // Get the first day of the calendar (the Sunday before or on the first day of the month)
        $firstDayOfCalendar = $firstDayOfMonth->copy();
        if ($firstDayOfCalendar->dayOfWeek !== 0) { // 0 is Sunday
            $firstDayOfCalendar->subDays($firstDayOfCalendar->dayOfWeek);
        }
        
        // Get the last day of the calendar (the Saturday after or on the last day of the month)
        $lastDayOfCalendar = $lastDayOfMonth->copy();
        if ($lastDayOfCalendar->dayOfWeek !== 6) { // 6 is Saturday
            $lastDayOfCalendar->addDays(6 - $lastDayOfCalendar->dayOfWeek);
        }
        
        // Create the calendar array
        $calendar = [];
        $currentDay = $firstDayOfCalendar->copy();
        
        while ($currentDay <= $lastDayOfCalendar) {
            $calendar[] = $currentDay->day;
            $currentDay->addDay();
        }
        
        return $calendar;
    }
}

