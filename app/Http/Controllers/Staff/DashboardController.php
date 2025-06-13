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
        // Get the timestamp from the request, or use current time
        $timestamp = $request->input('timestamp', now()->timestamp);
        
        // Create a Carbon instance from the timestamp
        $currentDate = Carbon::createFromTimestamp($timestamp);
        
        // Get previous and next month timestamps for navigation
        $lastMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        // Format timestamps for URLs
        $lastMonthTimestamp = $lastMonth->timestamp;
        $nextMonthTimestamp = $nextMonth->timestamp;
        
        // Format current month and year for display
        $currentMonth = $currentDate->format('F');
        $currentYear = $currentDate->format('Y');
        
        // Generate calendar data
        $calendar = $this->generateCalendarData($currentDate);
        
        // Return view with data
        return view('staff.dashboard', compact(
            'calendar',
            'currentMonth',
            'currentYear',
            'lastMonthTimestamp',
            'nextMonthTimestamp'
        ));
    }
    
    /**
     * Generate calendar data for the given month
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
                    'date' => $currentDay->format('Y-m-d'), // Store as string instead of Carbon object
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
















