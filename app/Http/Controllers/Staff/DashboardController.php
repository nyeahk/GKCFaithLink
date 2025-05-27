<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:staff');
    }

    public function index(Request $request)
    {
        // Check if user is staff
        if (Auth::user()->role !== 'staff') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('staff.login')
                ->with('error', 'You do not have staff privileges. Please login with a staff account.');
        }

        // Get timestamp from request or use current time
        $timestamp = $request->input('timestamp', now()->timestamp);
        $currentDate = Carbon::createFromTimestamp($timestamp);
        
        // Calculate next and previous month timestamps
        $nextMonthTimestamp = $currentDate->copy()->addMonth()->timestamp;
        $lastMonthTimestamp = $currentDate->copy()->subMonth()->timestamp;
        
        // Generate calendar data
        $calendar = $this->generateCalendar($currentDate);
        
        // Get counts for dashboard stats
        $announcementsCount = Announcement::count();
        $eventsCount = Event::where('start_date', '>=', now()->format('Y-m-d'))->count();
        $membersCount = User::where('role', 'member')->where('is_active', true)->count();
        
        return view('staff.dashboard', compact(
            'calendar',
            'currentDate',
            'nextMonthTimestamp',
            'lastMonthTimestamp',
            'announcementsCount',
            'eventsCount',
            'membersCount'
        ));
    }
    
    private function generateCalendar($date)
    {
        $calendar = [];
        
        // Get the first day of the month
        $firstDayOfMonth = $date->copy()->startOfMonth();
        
        // Get the last day of the month
        $lastDayOfMonth = $date->copy()->endOfMonth();
        
        // Get the first day of the calendar (might be in the previous month)
        $firstDayOfCalendar = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Get the last day of the calendar (might be in the next month)
        $lastDayOfCalendar = $lastDayOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        // Get all events for this calendar period
        $events = Event::whereBetween('start_date', [
            $firstDayOfCalendar->format('Y-m-d'),
            $lastDayOfCalendar->format('Y-m-d')
        ])->get();
        
        // Group events by date
        $eventsByDate = [];
        foreach ($events as $event) {
            $eventDate = Carbon::parse($event->start_date)->format('Y-m-d');
            if (!isset($eventsByDate[$eventDate])) {
                $eventsByDate[$eventDate] = [];
            }
            $eventsByDate[$eventDate][] = $event;
        }
        
        // Build the calendar array
        $currentDay = $firstDayOfCalendar->copy();
        $today = Carbon::today()->format('Y-m-d');
        
        while ($currentDay <= $lastDayOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dayFormatted = $currentDay->format('Y-m-d');
                $dayData = [
                    'day' => $currentDay->day,
                    'date' => $currentDay->copy(),
                    'isCurrentMonth' => $currentDay->month === $date->month,
                    'isToday' => $dayFormatted === $today,
                ];
                
                // Add events for this day if they exist
                if (isset($eventsByDate[$dayFormatted])) {
                    $dayData['events'] = $eventsByDate[$dayFormatted];
                }
                
                $week[] = $dayData;
                $currentDay->addDay();
            }
            $calendar[] = $week;
        }
        
        return $calendar;
    }

    public function getEventsForDate(Request $request)
    {
        $date = $request->input('date');
        $events = Event::whereDate('start_date', $date)->get();
        
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }

    public function getEventDetails($id)
    {
        $event = Event::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }
}
