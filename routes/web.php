<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\DonationController as MemberDonationController;  
use App\Http\Controllers\Member\AnnouncementController as MemberAnnouncementController;
use App\Http\Controllers\Member\EventController as MemberEventController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\RoleMiddleware;

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

// Public Routes
Route::get('/', function () {
    return view('auth.login');
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Profile routes - accessible by all authenticated users   
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Admin routes - only accessible by users with role 1 (admin)
    Route::prefix('admin')->name('admin.')->middleware([CheckUserActive::class, RoleMiddleware::class.':1'])->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('events/date/{date}', [DashboardController::class, 'getEventsForDate'])->name('events.date');
        
        // Events - view only for admin
        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
        
        // Other admin routes...
        // Reports
        Route::get('/reports/weekly', [ReportsController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
        // user management
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::patch('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');

        Route::get('reports/weekly', [ReportsController::class, 'weekly'])->name('reports.weekly');
        Route::get('reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
        Route::get('reports/weekly/download', [ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
        Route::get('reports/monthly/download', [ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');

        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

    // Staff routes - only accessible by users with role 2 (staff)
    Route::prefix('staff')->name('staff.')->middleware([CheckUserActive::class, RoleMiddleware::class.':2'])->group(function () {
        // Dashboard
        Route::get('dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        
        // Events - full CRUD for staff
        Route::get('events', [StaffEventController::class, 'index'])->name('events.index');
        Route::get('events/create', [StaffEventController::class, 'create'])->name('events.create');
        Route::post('events', [StaffEventController::class, 'store'])->name('events.store');
        Route::get('events/{event}', [StaffEventController::class, 'show'])->name('events.show');
        Route::get('events/{event}/edit', [StaffEventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [StaffEventController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [StaffEventController::class, 'destroy'])->name('events.destroy');
        Route::get('events/date/{date}', [StaffDashboardController::class, 'getEventsForDate'])->name('events.date');
        
        // Other staff routes...
    });

    // Member routes - only accessible by users with role 3 (member)
    Route::prefix('member')->name('member.')->middleware([CheckUserActive::class, RoleMiddleware::class.':3'])->group(function () {
        Route::get('dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        
        // Profile routes for members
        Route::get('profile', [App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [App\Http\Controllers\Member\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/password', [App\Http\Controllers\Member\ProfileController::class, 'password'])->name('profile.password');
        Route::put('profile/password', [App\Http\Controllers\Member\ProfileController::class, 'updatePassword'])->name('profile.password.update');
        
        // Donation routes for members
        Route::get('donations', [MemberDonationController::class, 'index'])->name('donations.index');
        Route::get('donations/create', [MemberDonationController::class, 'create'])->name('donations.create');
        Route::post('donations', [MemberDonationController::class, 'store'])->name('donations.store');
        Route::get('donations/{donation}', [MemberDonationController::class, 'show'])->name('donations.show');
        
        // Events - view only for members
        Route::get('events', [MemberEventController::class, 'index'])->name('events');
        Route::get('events/{event}', [MemberEventController::class, 'show'])->name('events.show');
        Route::get('events/date/{date}', [MemberDashboardController::class, 'getEventsForDate'])->name('events.date');
        
        // Announcements - view only for members
        Route::get('announcements', [MemberAnnouncementController::class, 'index'])->name('announcements');
        Route::get('announcements/{announcement}', [MemberAnnouncementController::class, 'show'])->name('announcements.show');
    });
});

// Notification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});








