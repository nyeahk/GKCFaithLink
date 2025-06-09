<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the staff dashboard.
     *
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
        
        // Get today's date for comparison
        $today = Carbon::today();
        
        // Get only current and upcoming events for this month (exclude past events)
        $firstDayOfMonth = $currentDate->copy()->startOfMonth();
        $lastDayOfMonth = $currentDate->copy()->endOfMonth();
        
        $events = Event::where('end_date', '>=', $today)
            ->whereBetween('start_date', [
                $firstDayOfMonth->copy()->startOfDay(),
                $lastDayOfMonth->copy()->endOfDay()
            ])
            ->get();
        
        \Log::info('Found ' . $events->count() . ' current/upcoming events for month ' . $currentDate->format('F Y'));
        
        // Get past events for archive section (limited to last 30 days for performance)
        $pastEvents = Event::where('end_date', '<', $today)
            ->where('start_date', '>=', $today->copy()->subDays(30))
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();
        
        // Generate calendar
        $calendar = [];
        
        // Get the first day of the calendar (might be in the previous month)
        $calendarStart = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Get the last day of the calendar (might be in the next month)
        $calendarEnd = $lastDayOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        // Generate weeks
        $currentDay = $calendarStart->copy();
        while ($currentDay->lte($calendarEnd)) {
            $week = [];
            
            // Generate days for this week
            for ($i = 0; $i < 7; $i++) {
                // Get events for this day (only current/upcoming)
                $dayEvents = $events->filter(function ($event) use ($currentDay) {
                    return $event->start_date->format('Y-m-d') === $currentDay->format('Y-m-d');
                });
                
                // Add day to week
                $week[] = [
                    'day' => $currentDay->day,
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $currentDay->month === $currentDate->month,
                    'isToday' => $currentDay->isSameDay($today),
                    'events' => $dayEvents->count() > 0 ? $dayEvents : null
                ];
                
                // Move to next day
                $currentDay->addDay();
            }
            
            // Add week to calendar
            $calendar[] = $week;
        }
        
        // Calculate timestamps for navigation
        $lastMonthTimestamp = $currentDate->copy()->subMonth()->timestamp;
        $nextMonthTimestamp = $currentDate->copy()->addMonth()->timestamp;
        $todayTimestamp = now()->timestamp;
        
        // Get counts for dashboard stats - Fix: use 'role' instead of 'role_id'
        $membersCount = User::where('role', 3)->count();
        $eventsCount = Event::count();
        $announcementsCount = Announcement::count();
        
        // Get recent announcements
        $recentAnnouncements = Announcement::where('status', 'published')
            ->orderBy('posted_at', 'desc')
            ->take(5)
            ->get();
        
        // Get upcoming events
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();
        
        return view('staff.dashboard', compact(
            'currentMonth',
            'currentYear',
            'prevMonth',
            'prevYear',
            'nextMonthFormatted', // Use renamed variable
            'nextYearFormatted',  // Use renamed variable
            'calendar',
            'currentDate',
            'lastMonthTimestamp',
            'nextMonthTimestamp',
            'todayTimestamp',
            'membersCount',
            'eventsCount',
            'announcementsCount',
            'recentAnnouncements',
            'upcomingEvents',
            'pastEvents'
        ));
    }
    
    /**
     * Generate calendar data for the given month.
     *
     * @param  \Carbon\Carbon  $date
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
        
        // Get today's date for comparison
        $today = Carbon::today();
        
        // Get current and future events for this month (exclude past events)
        $currentDate = Carbon::now()->startOfDay();
        $events = Event::where(function($query) use ($currentDate) {
                $query->whereDate('start_date', '>=', $currentDate)
                      ->orWhereDate('end_date', '>=', $currentDate);
            })
            ->whereBetween('start_date', [$firstDayOfCalendar, $lastDayOfCalendar])
            ->get();
        
        // Group events by date
        $eventsByDate = [];
        foreach ($events as $event) {
            $eventDate = $event->start_date->format('Y-m-d');
            if (!isset($eventsByDate[$eventDate])) {
                $eventsByDate[$eventDate] = [];
            }
            $eventsByDate[$eventDate][] = $event;
        }
        
        // Generate the calendar data
        $calendar = [];
        $currentDay = $firstDayOfCalendar->copy();
        
        while ($currentDay->lte($lastDayOfCalendar)) {
            $week = [];
            
            for ($i = 0; $i < 7; $i++) {
                $dayData = [
                    'day' => $currentDay->day,
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $currentDay->month === $date->month,
                    'isToday' => $currentDay->isSameDay($today)
                ];
                
                // Add events for this day if any
                $currentDayStr = $currentDay->format('Y-m-d');
                if (isset($eventsByDate[$currentDayStr])) {
                    $dayData['events'] = $eventsByDate[$currentDayStr];
                }
                
                $week[] = $dayData;
                $currentDay->addDay();
            }
            
            $calendar[] = $week;
        }
        
        return $calendar;
    }
    
    /**
     * Get events for a specific date (AJAX request)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventsForDate(Request $request)
    {
        try {
            // Get the date from the request
            $date = $request->input('date');
            
            if (!$date) {
                \Log::warning('Date parameter is missing in events request');
                return response()->json([
                    'date' => 'Unknown Date',
                    'events' => []
                ]);
            }
            
            // Parse the date
            $parsedDate = Carbon::parse($date);
            
            \Log::info('Fetching events for date: ' . $parsedDate->format('Y-m-d'));
            
            // Get events for the selected date - use whereDate to match only the date part
            $events = Event::whereDate('start_date', $parsedDate->format('Y-m-d'))
                ->orderBy('start_date', 'asc')
                ->get();
                
            \Log::info('Found ' . $events->count() . ' events for date ' . $parsedDate->format('Y-m-d'));
            
            // Debug log the actual events found
            foreach ($events as $event) {
                \Log::info("Event ID: {$event->id}, Title: {$event->title}, Start Date: {$event->start_date}");
            }
            
            // Map events to the required format
            $mappedEvents = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'time' => $event->start_date->format('g:i A') . ' - ' . $event->end_date->format('g:i A'), // 12-hour format
                    'location' => $event->location ?? 'No location specified',
                    'status' => ucfirst($event->status ?? 'unknown'),
                    'url' => route('staff.events.show', $event->id)
                ];
            });
            
            return response()->json([
                'date' => $parsedDate->format('F d, Y'),
                'events' => $mappedEvents
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getEventsForDate: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            return response()->json([
                'date' => $request->input('date') ? Carbon::parse($request->input('date'))->format('F d, Y') : 'Unknown Date',
                'events' => []
            ], 500);
        }
    }
}














