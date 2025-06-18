<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\User;
use App\Models\Donation;

class DashboardController extends Controller
{
    /**
     * Get weekly donation data for the dashboard
     * 
     * @return array
     */
    protected function getWeeklyDonationData()
    {
        // Get the start and end of the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Get all donations for the current week
        $weeklyDonations = Donation::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();
        
        // Log for debugging
        \Log::info('Weekly donations query:', [
            'start_date' => $startOfWeek->toDateTimeString(),
            'end_date' => $endOfWeek->toDateTimeString(),
            'count' => $weeklyDonations->count(),
            'sql' => Donation::whereBetween('created_at', [$startOfWeek, $endOfWeek])->toSql()
        ]);
        
        // Calculate totals
        $totalTithes = $weeklyDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'tithe' || 
                   str_contains(strtolower($donation->purpose), 'tithe');
        })->sum('amount');
        
        $totalOfferings = $weeklyDonations->filter(function($donation) {
            return (strtolower($donation->purpose) === 'offering' || 
                    str_contains(strtolower($donation->purpose), 'offering')) &&
                   !str_contains(strtolower($donation->purpose), 'tithe') &&
                   !str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');
        
        $totalMissionFunds = $weeklyDonations->filter(function($donation) {
            return strtolower($donation->purpose) === 'mission' || 
                   str_contains(strtolower($donation->purpose), 'mission');
        })->sum('amount');
        
        $totalAmount = $weeklyDonations->sum('amount');
        
        // Get donation data by day for chart
        $donationDays = [];
        $donationAmounts = [];
        
        $currentDay = $startOfWeek->copy();
        while ($currentDay <= $endOfWeek) {
            $dayTotal = $weeklyDonations->filter(function($donation) use ($currentDay) {
                return $donation->created_at->format('Y-m-d') === $currentDay->format('Y-m-d');
            })->sum('amount');
            
            $donationDays[] = $currentDay->format('D');
            $donationAmounts[] = $dayTotal;
            
            $currentDay->addDay();
        }
        
        return [
            'weeklyDonations' => $weeklyDonations,
            'totalTithes' => $totalTithes,
            'totalOfferings' => $totalOfferings,
            'totalMissionFunds' => $totalMissionFunds,
            'totalAmount' => $totalAmount,
            'donationDays' => $donationDays,
            'donationAmounts' => $donationAmounts
        ];
    }

    /**
     * Display the admin dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Set timezone
        date_default_timezone_set('Asia/Manila');
        
        // Get current date
        $today = Carbon::now();
        $todayTimestamp = $today->timestamp;

        // Determine current date based on timestamp from GET request
        $currentTimestamp = $request->query('timestamp', $todayTimestamp);
        $currentDate = Carbon::createFromTimestamp($currentTimestamp);

        // Calculate previous and next month timestamps
        $lastMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        $lastMonthTimestamp = $lastMonth->timestamp;
        $nextMonthTimestamp = $nextMonth->timestamp;

        // Format current month and year for display
        $currentMonth = $currentDate->format('F');
        $currentYear = $currentDate->format('Y');
        
        // Format previous and next month/year for navigation
        $prevMonth = $lastMonth->format('m');
        $prevYear = $lastMonth->format('Y');
        $nextMonthFormatted = $nextMonth->format('m'); // Renamed to avoid conflict
        $nextYearFormatted = $nextMonth->format('Y');  // Renamed to avoid conflict

        // Generate calendar data
        $calendar = $this->generateCalendarData($currentDate);

        // Get counts for dashboard stats
        $membersCount = User::where('role', 3)->count();
        $treasurersCount = User::where('role', 2)->count();
        $staffCount = User::where('role', 4)->count();
        
        // Get archived/past events
        $archivedEvents = Event::where('end_date', '<', $today)
            ->orderBy('end_date', 'desc')
            ->take(10)
            ->get();
        
        // Get weekly donation data
        $weeklyDonationData = $this->getWeeklyDonationData();

        return view('admin.dashboard.dashboard', [
            'calendar' => $calendar,
            'currentDate' => $currentDate,
            'todayTimestamp' => $todayTimestamp,
            'lastMonthTimestamp' => $lastMonthTimestamp,
            'nextMonthTimestamp' => $nextMonthTimestamp,
            'membersCount' => $membersCount,
            'treasurersCount' => $treasurersCount,
            'staffCount' => $staffCount,
            'archivedEvents' => $archivedEvents,
            'weeklyDonations' => $weeklyDonationData['weeklyDonations'],
            'totalTithes' => $weeklyDonationData['totalTithes'],
            'totalOfferings' => $weeklyDonationData['totalOfferings'],
            'totalMissionFunds' => $weeklyDonationData['totalMissionFunds'],
            'totalAmount' => $weeklyDonationData['totalAmount'],
            'donationDays' => $weeklyDonationData['donationDays'],
            'donationAmounts' => $weeklyDonationData['donationAmounts']
        ]);
    }
    /**
     * Generate calendar data for the given month
     *
     * @param Carbon $date
     * @return array
     */
    protected function generateCalendarData($date)
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
        
        // Get today's date for highlighting in Philippines timezone
        $today = Carbon::now('Asia/Manila')->startOfDay();
        
        // Get ONLY current and future events for the month (exclude past events)
        $events = Event::where('end_date', '>=', $today)
            ->whereBetween('start_date', [
                $firstDayOfCalendar->copy()->startOfDay(),
                $lastDayOfCalendar->copy()->endOfDay()
            ])
            ->get()
            ->groupBy(function ($event) {
                return Carbon::parse($event->start_date)->format('Y-m-d');
            });
        
        // Build the calendar array
        $calendar = [];
        $currentDay = $firstDayOfCalendar->copy();
        
        while ($currentDay <= $lastDayOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $isCurrentMonth = $currentDay->month === $date->month;
                $isToday = $currentDay->format('Y-m-d') === $today->format('Y-m-d');
                
                $dayData = [
                    'day' => $currentDay->day,
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $isCurrentMonth,
                    'isToday' => $isToday
                ];
                
                // Add events for this day if any exist
                $dateKey = $currentDay->format('Y-m-d');
                if (isset($events[$dateKey])) {
                    $dayData['events'] = $events[$dateKey];
                }
                
                $week[] = $dayData;
                $currentDay->addDay();
            }
            $calendar[] = $week;
        }
        
        return $calendar;
    }

    public function getEventsForDate(Request $request, $date)
    {
        $date = Carbon::parse($date);
        $currentDate = Carbon::now()->startOfDay();
        
        // Only get current and future events
        $events = Event::whereDate('start_date', $date)
            ->where(function($query) use ($currentDate) {
                $query->whereDate('start_date', '>=', $currentDate)
                      ->orWhereDate('end_date', '>=', $currentDate);
            })
            ->select('id', 'title', 'start_date', 'end_date', 'location', 'status', 'description')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_time' => Carbon::parse($event->start_date)->format('g:i A'), // 12-hour format with AM/PM
                    'end_time' => Carbon::parse($event->end_date)->format('g:i A'),     // 12-hour format with AM/PM
                    'location' => $event->location,
                    'status' => ucfirst($event->status),
                    'status_class' => $this->getStatusClass($event->status),
                    'description' => $event->description
                ];
            });

        return response()->json([
            'events' => $events,
            'date' => $date->format('F j, Y'),
            'hasEvents' => $events->count() > 0
        ]);
    }

    private function getStatusClass($status)
    {
        switch ($status) {
            case 'published':
                return 'bg-success';
            case 'cancelled':
                return 'bg-danger';
            default:
                return 'bg-primary';
        }
    }
}









