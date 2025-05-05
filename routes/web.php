<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Member\AnnouncementController as MemberAnnouncementController;
use App\Http\Controllers\EventController;

// Landing page
Route::get('/', function () {
    return view('gkcfaithlink');
});

// Authentication Routes - These must be accessible to guests
Route::get('/login', [AuthenticationController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('auth.login.post');
Route::get('/register', [AuthenticationController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthenticationController::class, 'register'])->name('auth.register.post');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    
    // Events
    Route::prefix('member/events')->group(function () {
        Route::get('/{date?}', [MemberDashboardController::class, 'showEventsByDate'])
            ->name('member.events')
            ->defaults('date', 'today');
        Route::post('/{event}/register', [EventController::class, 'register'])
            ->name('member.events.register');
    });
    
    // Donations
    Route::prefix('donations')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('member.donations');
        Route::post('/', [DonationController::class, 'store'])->name('member.donations.store');
        Route::get('/{id}/receipt', [DonationController::class, 'showReceipt'])->name('member.donations.receipt');
    });
    
    // Announcements
    Route::resource('announcements', AnnouncementController::class);
    Route::delete('/announcements/{announcement}/attachment', [AnnouncementController::class, 'deleteAttachment'])
        ->name('announcements.attachment.delete');
    
    // Member Announcements
    Route::prefix('member/announcements')->group(function () {
        Route::get('/', [MemberAnnouncementController::class, 'index'])->name('member.announcements');
        Route::get('/{id}', [MemberAnnouncementController::class, 'show'])->name('member.announcements.show');
    });

    Route::get('/member/profile', [MemberController::class, 'profile'])->name('member.profile');
    Route::put('/member/profile', [MemberController::class, 'updateProfile'])->name('member.profile.update');
});




