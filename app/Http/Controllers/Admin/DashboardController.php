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
        $lastMonthTimestamp = Carbon::create($currentDate->year, $currentDate->month - 1, $currentDate->day, 0, 0, 0)->timestamp;
        $nextMonthTimestamp = Carbon::create($currentDate->year, $currentDate->month + 1, $currentDate->day, 0, 0, 0)->timestamp;

        // Get statistics
        $announcementsCount = Announcement::count();
        $eventsCount = Event::where('start_date', '>=', $today)->count();
        $membersCount = Member::where('status', 'active')->count();
        $donationsTotal = Donation::sum('amount');
        $totalTithes = Donation::where('purpose', 'like', '%tithe%')->sum('amount');

        // Get recent activities
        $recentEvents = Event::latest()->take(5)->get();

        // Prepare calendar data
        $calendar = [];
        $numOfDaysForThisMonth = $currentDate->daysInMonth;
        $startingWeekDay = Carbon::create($currentDate->year, $currentDate->month, 1)->dayOfWeek;
        
        $dayCounter = 1;
        $daysInAWeek = 7;
        
        // Calculate previous month's last days
        $prevMonth = $currentDate->copy()->subMonth();
        $prevMonthDays = $prevMonth->daysInMonth;
        $prevMonthStartDay = $prevMonthDays - $startingWeekDay + 1;
        
        while ($dayCounter <= $numOfDaysForThisMonth) {
            $week = [];
            $colCounter = 0;
            
            while ($colCounter < $daysInAWeek) {
                if ($colCounter === 0 && $dayCounter === 1) {
                    // Handle the first day of the month
                    for ($i = 0; $i < $startingWeekDay; $i++) {
                        $prevDate = Carbon::create($prevMonth->year, $prevMonth->month, $prevMonthStartDay + $i);
                        $week[] = [
                            'day' => $prevMonthStartDay + $i,
                            'date' => $prevDate,
                            'isToday' => false,
                            'isCurrentMonth' => false,
                            'events' => null,
                            'moonPhase' => $this->getMoonPhase($prevDate)
                        ];
                        $colCounter++;
                    }
                }
                
                if ($dayCounter > $numOfDaysForThisMonth) {
                    // Fill remaining days with next month's days
                    $nextMonth = $currentDate->copy()->addMonth();
                    $nextMonthDay = 1;
                    while ($colCounter < $daysInAWeek) {
                        $nextDate = Carbon::create($nextMonth->year, $nextMonth->month, $nextMonthDay);
                        $week[] = [
                            'day' => $nextMonthDay,
                            'date' => $nextDate,
                            'isToday' => false,
                            'isCurrentMonth' => false,
                            'events' => null,
                            'moonPhase' => $this->getMoonPhase($nextDate)
                        ];
                        $colCounter++;
                        $nextMonthDay++;
                    }
                    break;
                }
                
                $date = Carbon::create($currentDate->year, $currentDate->month, $dayCounter);
                $events = Event::whereDate('start_date', $date)
                    ->select('id', 'title', 'start_date', 'end_date', 'location', 'status')
                    ->get();
                
                $week[] = [
                    'day' => $dayCounter,
                    'date' => $date,
                    'isToday' => $date->isToday(),
                    'isCurrentMonth' => true,
                    'events' => $events->count() > 0 ? $events : null,
                    'moonPhase' => $this->getMoonPhase($date)
                ];
                
                $colCounter++;
                $dayCounter++;
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
            'recentEvents'
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

    private function getMoonPhase($date)
    {
        // Calculate moon phase (0 = New Moon, 0.25 = First Quarter, 0.5 = Full Moon, 0.75 = Last Quarter)
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;
        
        $c = floor(($month + 9) / 12);
        $y = $year + 4800 - $c;
        $m = $month + 12 * $c - 3;
        
        $julian = $day + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
        
        $daysSinceNew = $julian - 2451549.5;
        $newMoons = $daysSinceNew / 29.53;
        $phase = $newMoons - floor($newMoons);
        
        if ($phase < 0.03 || $phase > 0.97) {
            return '🌑 New Moon';
        } elseif ($phase < 0.22) {
            return '🌒 Waxing Crescent';
        } elseif ($phase < 0.28) {
            return '🌓 First Quarter';
        } elseif ($phase < 0.47) {
            return '🌔 Waxing Gibbous';
        } elseif ($phase < 0.53) {
            return '🌕 Full Moon';
        } elseif ($phase < 0.72) {
            return '🌖 Waning Gibbous';
        } elseif ($phase < 0.78) {
            return '🌗 Last Quarter';
        } else {
            return '🌘 Waning Crescent';
        }
    }
}