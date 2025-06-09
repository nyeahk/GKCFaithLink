<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Announcement;

class DashboardController extends Controller
{
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
        
        // Get upcoming events
        $upcomingEvents = Event::where('start_date', '>=', $today)
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();
            
        // Get recent announcements
        $recentAnnouncements = Announcement::where('status', 'published')
            ->orderBy('posted_at', 'desc')
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'currentMonth',
            'currentYear',
            'prevMonth',
            'prevYear',
            'nextMonthFormatted', // Use renamed variable
            'nextYearFormatted',  // Use renamed variable
            'calendar',
            'currentDate',
            'todayTimestamp',
            'lastMonthTimestamp',
            'nextMonthTimestamp',
            'upcomingEvents',
            'recentAnnouncements'
        ));
    }
    
    public function getEventsForDate($date)
    {
        // Convert the date string to a Carbon instance
        $dateObj = Carbon::parse($date);
        $currentDate = Carbon::now()->startOfDay();
        
        // Get all current and future events for this date
        $events = Event::whereDate('start_date', $dateObj)
            ->where(function($query) use ($currentDate) {
                $query->whereDate('start_date', '>=', $currentDate)
                      ->orWhereDate('end_date', '>=', $currentDate);
            })
            ->where('status', 'published')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start_time' => $event->start_date->format('g:i A'), // 12-hour format
                    'end_time' => $event->end_date->format('g:i A'),     // 12-hour format
                    'location' => $event->location
                ];
            });
        
        return response()->json([
            'date' => $dateObj->format('F d, Y'),
            'hasEvents' => $events->count() > 0,
            'events' => $events
        ]);
    }
    
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
            ->where('status', 'published')
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
}









