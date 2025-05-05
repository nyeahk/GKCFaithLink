<?php
// filepath: c:\Users\Datawords\Documents\GitHub\GKCFaithLink\app\Http\Controllers\Admin\DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Member;
use App\Models\Donation;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $today = Carbon::now();
        $todayTimestamp = $today->timestamp;

        // Determine current date based on timestamp from GET request
        $currentTimestamp = $request->query('timestamp', $todayTimestamp);
        $currentDate = Carbon::createFromTimestamp($currentTimestamp);

        // Calculate previous and next month timestamps
        $lastMonthTimestamp = $currentDate->copy()->subMonth()->timestamp;
        $nextMonthTimestamp = $currentDate->copy()->addMonth()->timestamp;

        // Get statistics
        $announcementsCount = Announcement::count();
        $eventsCount = Event::where('start_date', '>=', $today)->count();
        $membersCount = Member::where('status', 'active')->count();
        $donationsTotal = Donation::sum('amount');
        $totalTithes = Donation::where('purpose', 'like', '%tithe%')->sum('amount');

        // Get recent activities
        $recentActivities = Activity::latest()->take(5)->get();

        // Prepare calendar data
        $calendar = [];
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startDate = $startOfMonth->copy()->startOfWeek();
        $endDate = $endOfMonth->copy()->endOfWeek();

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $events = Event::whereDate('start_date', $currentDate)
                    ->select('id', 'title', 'start_date', 'end_date', 'location', 'status')
                    ->get();
                    
                Log::info('Events for date ' . $currentDate->format('Y-m-d') . ':', [
                    'count' => $events->count(),
                    'events' => $events->toArray()
                ]);
                
                $week[] = [
                    'day' => $currentDate->day,
                    'date' => $currentDate->copy(),
                    'isToday' => $currentDate->isToday(),
                    'isCurrentMonth' => $currentDate->month === $startOfMonth->month,
                    'events' => $events->count() > 0 ? $events : null
                ];
                $currentDate->addDay();
            }
            $calendar[] = $week;
        }

        return view('admin.dashboard.index', compact(
            'todayTimestamp',
            'lastMonthTimestamp',
            'nextMonthTimestamp',
            'currentDate',
            'calendar',
            'announcementsCount',
            'eventsCount',
            'membersCount',
            'donationsTotal',
            'totalTithes',
            'recentActivities'
        ));
    }

    public function getEventsForDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        
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
                    'status' => $event->status,
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

    public function getEventDetails($id)
    {
        try {
            $event = Event::findOrFail($id);
            
            $response = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_date' => Carbon::parse($event->start_date)->format('F j, Y'),
                'start_time' => Carbon::parse($event->start_date)->format('h:i A'),
                'end_date' => Carbon::parse($event->end_date)->format('F j, Y'),
                'end_time' => Carbon::parse($event->end_date)->format('h:i A'),
                'location' => $event->location,
                'status' => $event->status,
                'status_class' => $this->getStatusClass($event->status)
            ];
            
            Log::info('Event details response:', $response);
            
            return response()->json($response, 200, [
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching event details:', [
                'error' => $e->getMessage(),
                'event_id' => $id
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch event details: ' . $e->getMessage()
            ], 500, [
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]);
        }
    }

    private function getStatusClass($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}