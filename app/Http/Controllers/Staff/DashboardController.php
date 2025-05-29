<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:staff');
    }

    public function index(Request $request)
    {
        // Log the request for debugging
        Log::info('Staff DashboardController: index method called by user ' . Auth::id());
        
        // Check if user is staff or admin
        if (Auth::user()->role !== 'staff' && Auth::user()->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'You do not have staff privileges. Please login with a staff account.');
        }
        
        // Get counts for dashboard stats
        $announcementsCount = Announcement::count();
        $eventsCount = Event::where('start_date', '>=', Carbon::now())->count();
        $membersCount = Member::count();
        
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
        
        return view('staff.dashboard', compact(
            'announcementsCount',
            'eventsCount',
            'membersCount',
            'currentDate',
            'lastMonthTimestamp',
            'nextMonthTimestamp',
            'todayTimestamp',
            'calendar',
            'daysInAWeek'
        ));
    }
    
    /**
     * Generate calendar data for the given month
     */
    private function generateCalendar($date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Get the day of week for the first day of month (0 = Sunday, 6 = Saturday)
        $firstDayOfWeek = $startOfMonth->dayOfWeek;
        
        // Get the total days in the month
        $daysInMonth = $date->daysInMonth;
        
        // Create calendar array
        $calendar = [];
        
        // Add empty cells for days before the first day of month
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendar[] = [
                'day' => null,
                'date' => null,
                'isToday' => false,
                'isCurrentMonth' => false
            ];
        }
        
        // Add cells for each day of the month
        $today = Carbon::today();
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($date->year, $date->month, $day);
            $calendar[] = [
                'day' => $day,
                'date' => $currentDate,
                'isToday' => $currentDate->isSameDay($today),
                'isCurrentMonth' => true
            ];
        }
        
        // Fill remaining cells to complete the grid (if needed)
        $totalCells = count($calendar);
        $cellsToAdd = ceil($totalCells / 7) * 7 - $totalCells;
        
        for ($i = 0; $i < $cellsToAdd; $i++) {
            $calendar[] = [
                'day' => null,
                'date' => null,
                'isToday' => false,
                'isCurrentMonth' => false
            ];
        }
        
        return $calendar;
    }

    public function getEventsForDate(Request $request)
    {
        // Log the request for debugging
        Log::info('Staff DashboardController: getEventsForDate method called by user ' . Auth::id());
        
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }
        
        $selectedDate = Carbon::parse($date);
        
        // Get events for the selected date
        $events = Event::whereDate('start_date', '<=', $selectedDate)
            ->whereDate('end_date', '>=', $selectedDate)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_time' => $event->start_date->format('h:i A'),
                    'end_time' => $event->end_date->format('h:i A'),
                    'location' => $event->location,
                    'status' => ucfirst($event->status)
                ];
            });
        
        return response()->json([
            'date' => $selectedDate->format('F j, Y'),
            'events' => $events
        ]);
    }
}

