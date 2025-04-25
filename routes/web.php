<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MemberDashboardController;

use App\Http\Controllers\MemberController;

Route::get('/gkcfaithlink', function () {
    return view('member.gkcfaithlink');
});

 Route::get('/member-login', function () {
     return view('member.member-login');
 });

 Route::get('/member-signup', [MemberController::class, 'signup'])->name('member.member-signup');
 
 Route::get('/member-login', [MemberController::class, 'login'])->name('member.member-login');

 Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');

Route::get('/events', function () {
    return view('member.events');
})->name('member.events');

Route::get('/donations', function () {
    return view('member.donations');
})->name('member.donations');

Route::get('/announcements', function () {
    return view('member.announcements');
})->name('member.announcements');

Route::get('/profile', function () {
    return view('member.profile');
})->name('member.profile');



