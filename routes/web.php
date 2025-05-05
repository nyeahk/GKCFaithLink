<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/events', [DashboardController::class, 'getEventsForDate'])->name('admin.dashboard.events');
        Route::get('/dashboard/events/{id}', [DashboardController::class, 'getEventDetails'])->name('admin.dashboard.event.details');
        
        // Event routes
        Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/events', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('admin.events.show');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');

        // Announcement routes
        Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/announcements/create', [\App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('/announcements/{id}/edit', [\App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Donation routes
        Route::get('/donations', [DonationController::class, 'index'])->name('admin.donations.index');
        Route::get('/donations/qr-code', [DonationController::class, 'showQrCode'])->name('admin.donations.qr-code');
        Route::get('/donations/manual', [\App\Http\Controllers\Admin\DonationController::class, 'manualCreate'])->name('admin.donations.manual-create');
        Route::post('/donations/manual', [\App\Http\Controllers\Admin\DonationController::class, 'manualStore'])->name('admin.donations.manual-store');
        Route::get('/donations/create', [DonationController::class, 'create'])->name('admin.donations.create');
        Route::post('/donations', [DonationController::class, 'store'])->name('admin.donations.store');
        Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('admin.donations.show');
        Route::post('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('admin.donations.approve');
        Route::post('/donations/{donation}/decline', [DonationController::class, 'decline'])->name('admin.donations.decline');
        Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('admin.donations.edit');
        Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('admin.donations.update');
        Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('admin.donations.destroy');

        // Report routes
        Route::get('/reports/weekly', [\App\Http\Controllers\Admin\ReportsController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [\App\Http\Controllers\Admin\ReportsController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/weekly/download', [\App\Http\Controllers\Admin\ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
        Route::get('/reports/monthly/download', [\App\Http\Controllers\Admin\ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');

        // Member routes
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});