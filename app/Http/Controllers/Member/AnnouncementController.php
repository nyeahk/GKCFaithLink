<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('member.announcements');
    }

    public function show($id)
    {
        return view('member.announcements', compact('id'));
    }
}
