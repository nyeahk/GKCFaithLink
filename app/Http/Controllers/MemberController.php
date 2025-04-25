<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function login()
    {
        return view('member.member-login');
    }

    public function signup()
    {
        return view('member.member-signup');
    }
}