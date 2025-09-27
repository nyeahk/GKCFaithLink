<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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
use App\Http\Controllers\Treasurer\DashboardController as TreasurerDashboardController;
use App\Http\Controllers\Treasurer\DonationController as TreasurerDonationController;
use App\Http\Controllers\Treasurer\ProfileController as TreasurerProfileController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;


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

//Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Profile routes - accessible by all authenticated users   
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Notification routes - accesible by all authenticated users
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');


    // Admin routes - only accessible by users with role 1 (admin)
    Route::prefix('admin')->name('admin.')->middleware([CheckUserActive::class, RoleMiddleware::class.':1'])->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('events/date/{date}', [DashboardController::class, 'getEventsForDate'])->name('events.date');
        
        // Events - view only for admin
        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
        
        // Donations
        Route::get('donations', [AdminDonationController::class, 'index'])->name('donations.index');
        Route::get('donations/create', [AdminDonationController::class, 'create'])->name('donations.create');
        Route::get('donations/manual-create', [AdminDonationController::class, 'manualCreate'])->name('donations.manual-create');
        Route::post('donations', [AdminDonationController::class, 'store'])->name('donations.store');
        Route::post('donations/manual', [AdminDonationController::class, 'manualStore'])->name('donations.manual-store');
        Route::get('donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
        Route::get('donations/{donation}/edit', [AdminDonationController::class, 'edit'])->name('donations.edit');
        Route::put('donations/{donation}', [AdminDonationController::class, 'update'])->name('donations.update');
        Route::delete('donations/{donation}', [AdminDonationController::class, 'destroy'])->name('donations.destroy');
        Route::post('donations/{donation}/approve', [AdminDonationController::class, 'approve'])->name('donations.approve');
        Route::post('donations/{donation}/decline', [AdminDonationController::class, 'decline'])->name('donations.decline');
        
        // Other admin routes...
        // Reports
        Route::get('/reports/weekly', [ReportsController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
        // user management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');
    Route::patch('users/{user}/assign-role', [AdminUserController::class, 'assignRole'])->name('users.assignRole');
    Route::patch('users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');

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

    // Treasurer routes - only accessible by users with role 2 (treasurer)
    Route::prefix('treasurer')->name('treasurer.')->middleware([CheckUserActive::class, RoleMiddleware::class.':2'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [TreasurerDashboardController::class, 'index'])->name('dashboard');
        
        // Donations
        Route::get('/donations', [TreasurerDonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/create', [TreasurerDonationController::class, 'create'])->name('donations.create');
        Route::post('/donations', [TreasurerDonationController::class, 'store'])->name('donations.store');
        Route::get('/donations/{donation}', [TreasurerDonationController::class, 'show'])->name('donations.show');
        Route::get('/donations/{donation}/edit', [TreasurerDonationController::class, 'edit'])->name('donations.edit');
        Route::put('/donations/{donation}', [TreasurerDonationController::class, 'update'])->name('donations.update');
        Route::delete('/donations/{donation}', [TreasurerDonationController::class, 'destroy'])->name('donations.destroy');
        
        // Add these new routes for verifying and declining donations
        Route::post('/donations/{donation}/verify', [TreasurerDonationController::class, 'verify'])->name('donations.verify');
        Route::post('/donations/{donation}/decline', [TreasurerDonationController::class, 'decline'])->name('donations.decline');
        
        // Reports
        Route::get('/reports', [App\Http\Controllers\Treasurer\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/weekly', [App\Http\Controllers\Treasurer\ReportsController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [App\Http\Controllers\Treasurer\ReportsController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/weekly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
        Route::get('/reports/monthly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');
        
        // Profile routes for treasurer
        Route::get('profile', [TreasurerProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [TreasurerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [TreasurerProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/password', [TreasurerProfileController::class, 'password'])->name('profile.password');
        Route::put('profile/password', [TreasurerProfileController::class, 'updatePassword'])->name('profile.password.update');
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

    // Staff routes - only accessible by users with role 4 (staff)
    Route::prefix('staff')->name('staff.')->middleware([CheckUserActive::class, RoleMiddleware::class.':4'])->group(function () {
        // Dashboard
        Route::get('dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
        
        // Events - full CRUD for staff
        Route::get('events', [App\Http\Controllers\Staff\EventController::class, 'index'])->name('events.index');
        Route::get('events/create', [App\Http\Controllers\Staff\EventController::class, 'create'])->name('events.create');
        Route::post('events', [App\Http\Controllers\Staff\EventController::class, 'store'])->name('events.store');
        Route::get('events/{event}', [App\Http\Controllers\Staff\EventController::class, 'show'])->name('events.show');
        Route::get('events/{event}/edit', [App\Http\Controllers\Staff\EventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [App\Http\Controllers\Staff\EventController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [App\Http\Controllers\Staff\EventController::class, 'destroy'])->name('events.destroy');
        Route::get('events/date/{date}', [App\Http\Controllers\Staff\DashboardController::class, 'getEventsForDate'])->name('events.date');

        // Announcements - full CRUD for staff
        Route::get('announcements', [App\Http\Controllers\Staff\AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/create', [App\Http\Controllers\Staff\AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [App\Http\Controllers\Staff\AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('announcements/{announcement}/edit', [App\Http\Controllers\Staff\AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Users - view only for staff
        Route::get('users', [App\Http\Controllers\Staff\UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [App\Http\Controllers\Staff\UserController::class, 'show'])->name('users.show');

        // Profile routes for staff
        Route::get('profile', [App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [App\Http\Controllers\Staff\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/password', [App\Http\Controllers\Staff\ProfileController::class, 'password'])->name('profile.password');
        Route::put('profile/password', [App\Http\Controllers\Staff\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

    // Staff dashboard events endpoint
    Route::get('/staff/dashboard/events', [App\Http\Controllers\Staff\DashboardController::class, 'getEventsForDate'])
        ->name('staff.dashboard.events')
        ->middleware([CheckUserActive::class, RoleMiddleware::class.':4']);
});

// Member Event Registration Routes
Route::middleware(['auth', 'verified', CheckUserActive::class, RoleMiddleware::class.':3'])->prefix('member')->name('member.')->group(function () {
    // Event registration routes
    Route::get('/events/registrations', [App\Http\Controllers\Member\EventRegistrationController::class, 'index'])->name('events.registrations');
    Route::get('/events/{event}/register', [App\Http\Controllers\Member\EventRegistrationController::class, 'create'])->name('events.registration.create');
    Route::post('/events/{event}/register', [App\Http\Controllers\Member\EventRegistrationController::class, 'store'])->name('events.registration.store');
    Route::delete('/events/{event}/register', [App\Http\Controllers\Member\EventRegistrationController::class, 'cancel'])->name('events.registration.cancel');
});

// Treasurer Routes
Route::middleware(['auth', 'verified', CheckUserActive::class, RoleMiddleware::class.':2'])->prefix('treasurer')->name('treasurer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Treasurer\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('donations', TreasurerDonationController::class);
    
    // Make sure this route is defined correctly
    Route::post('/donations/{donation}/verify', [TreasurerDonationController::class, 'verify'])->name('donations.verify');
    Route::post('/donations/{donation}/decline', [TreasurerDonationController::class, 'decline'])->name('donations.decline');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Treasurer\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/weekly', [App\Http\Controllers\Treasurer\ReportsController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [App\Http\Controllers\Treasurer\ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/weekly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
    Route::get('/reports/monthly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');
});

// Reports routes - accessible by both admin and treasurer
Route::middleware(['auth', 'verified', CheckUserActive::class, RoleMiddleware::class.':1,2'])->group(function () {
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/weekly', [App\Http\Controllers\ReportController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [App\Http\Controllers\ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/weekly/download', [App\Http\Controllers\ReportController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
    Route::get('/reports/monthly/download', [App\Http\Controllers\ReportController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');
    
    // Add these routes for filtering
    Route::get('/reports/weekly/filter', [App\Http\Controllers\ReportController::class, 'filterWeekly'])->name('reports.weekly.filter');
    Route::get('/reports/monthly/filter', [App\Http\Controllers\ReportController::class, 'filterMonthly'])->name('reports.monthly.filter');
});

// Admin-specific report routes
Route::middleware(['auth', 'verified', CheckUserActive::class, RoleMiddleware::class.':1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/weekly', [App\Http\Controllers\Admin\ReportsController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [App\Http\Controllers\Admin\ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/weekly/download', [App\Http\Controllers\Admin\ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
    Route::get('/reports/monthly/download', [App\Http\Controllers\Admin\ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');
});

// Treasurer-specific report routes
Route::middleware(['auth', 'verified', CheckUserActive::class, RoleMiddleware::class.':2'])->prefix('treasurer')->name('treasurer.')->group(function () {
    Route::get('/reports', [App\Http\Controllers\Treasurer\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/weekly', [App\Http\Controllers\Treasurer\ReportsController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [App\Http\Controllers\Treasurer\ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/weekly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadWeeklyReport'])->name('reports.weekly.download');
    Route::get('/reports/monthly/download', [App\Http\Controllers\Treasurer\ReportsController::class, 'downloadMonthlyReport'])->name('reports.monthly.download');
});







