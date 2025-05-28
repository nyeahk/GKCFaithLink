<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;

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

    // Generate calendar data
    $calendar = $this->generateCalendarData($currentDate);

    return view('admin.dashboard.dashboard', [
        'calendar' => $calendar,
        'currentDate' => $currentDate,
        'todayTimestamp' => $todayTimestamp,
        'lastMonthTimestamp' => $lastMonthTimestamp,
        'nextMonthTimestamp' => $nextMonthTimestamp
    ]);
}
    /**
     * Generate calendar data for the given month
     *
     * @param Carbon $date
     * @return array
     */
    private function generateCalendarData(Carbon $date)
    {
        // Clone the date to avoid modifying the original
        $date = $date->copy();
        
        // Get the first day of the month
        $firstDayOfMonth = $date->copy()->startOfMonth();
        
        // Get the last day of the month
        $lastDayOfMonth = $date->copy()->endOfMonth();
        
        // Get the first day of the calendar (the Sunday before or on the first day of the month)
        $firstDayOfCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Get the last day of the calendar (the Saturday after or on the last day of the month)
        $lastDayOfCalendar = $lastDayOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        // Get today's date for highlighting
        $today = Carbon::today();
        
        // Get all events for the month
        $events = Event::whereBetween('start_date', [
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
            
            // Build a week
            for ($i = 0; $i < 7; $i++) {
                $dayData = [
                    'day' => $currentDay->day,
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $currentDay->month === $date->month,
                    'isToday' => $currentDay->isSameDay($today)
                ];
                
                // Add events for this day if any
                $dayKey = $currentDay->format('Y-m-d');
                if (isset($events[$dayKey])) {
                    $dayData['events'] = $events[$dayKey];
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
        
        $events = Event::whereDate('start_date', $date)
            ->select('id', 'title', 'start_date', 'end_date', 'location', 'status', 'description')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_time' => Carbon::parse($event->start_date)->format('h:i A'),
                    'end_time' => Carbon::parse($event->end_date)->format('h:i A'),
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