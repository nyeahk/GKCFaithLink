<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the month from the request or use current month
        $monthParam = $request->input('month');
        $carbon = $monthParam ? Carbon::createFromFormat('Y-m', $monthParam) : Carbon::now();
        
        $startOfMonth = $carbon->copy()->startOfMonth();
        $endOfMonth = $carbon->copy()->endOfMonth();
        $startDay = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endDay = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        $weeks = [];
        $date = $startDay->copy();

        while ($date <= $endDay) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = $date->copy();
                $date->addDay();
            }
            $weeks[] = $week;
        }

        // Get events for the current month
        $events = Event::whereMonth('event_date', $carbon->month)
                       ->whereYear('event_date', $carbon->year)
                       ->get()
                       ->groupBy(function($event) {
                           return $event->event_date->format('Y-m-d');
                       });

        return view('member.dashboard', [
            'weeks' => $weeks,
            'carbon' => $carbon,
            'events' => $events,
        ]);
    }

    public function showEventsByDate($date)
    {
        $dateObj = Carbon::parse($date);
        $events = Event::whereDate('event_date', $dateObj)->get();
        
        return view('member.events', [
            'date' => $dateObj,
            'events' => $events
        ]);
    }
}