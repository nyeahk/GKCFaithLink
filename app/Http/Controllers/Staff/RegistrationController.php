<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventRegistration; // Make sure to include the EventRegistration model

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = EventRegistration::with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('staff.registrations.index', compact('registrations'));
    }
}