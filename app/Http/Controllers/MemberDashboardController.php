<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $carbon = Carbon::now();
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

        return view('member.dashboard', [
            'weeks' => $weeks,
            'carbon' => $carbon,
        ]);
    }

    public function dashboard()
    {
        $events = Event::all(); // Replace 'Event' with the appropriate model name
        return view('member.dashboard', compact('events'));
    }
}