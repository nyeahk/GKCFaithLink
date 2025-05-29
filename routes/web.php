<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

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
    return view('auth.login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Staff Authentication Routes - Make sure these are defined BEFORE any catch-all routes
Route::get('/staff/login', [App\Http\Controllers\Auth\StaffLoginController::class, 'showLoginForm'])->name('staff.login')->middleware('web');
Route::post('/staff/login', [App\Http\Controllers\Auth\StaffLoginController::class, 'login'])->middleware('web');
Route::post('/staff/logout', [App\Http\Controllers\Auth\StaffLoginController::class, 'logout'])->name('staff.logout')->middleware('web');

// Staff Registration Routes
Route::get('/staff/register', [App\Http\Controllers\Auth\StaffRegisterController::class, 'showRegistrationForm'])->name('staff.register');
Route::post('/staff/register', [App\Http\Controllers\Auth\StaffRegisterController::class, 'register']);

// Staff Protected Routes - Make sure these are defined BEFORE any admin routes
Route::group(['prefix' => 'staff', 'middleware' => ['auth', 'staff']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('staff.dashboard');
    Route::get('/dashboard/events', [App\Http\Controllers\Staff\DashboardController::class, 'getEventsForDate'])->name('staff.dashboard.events');
    
    // Staff Profile routes
    Route::get('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('staff.profile.index');
    Route::put('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'update'])->name('staff.profile.update');
    
    // Staff Announcement Routes
    Route::get('/announcements', [App\Http\Controllers\Staff\AnnouncementController::class, 'index'])->name('staff.announcements.index');
    Route::get('/announcements/create', [App\Http\Controllers\Staff\AnnouncementController::class, 'create'])->name('staff.announcements.create');
    Route::post('/announcements', [App\Http\Controllers\Staff\AnnouncementController::class, 'store'])->name('staff.announcements.store');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'show'])->name('staff.announcements.show');
    Route::get('/announcements/{announcement}/edit', [App\Http\Controllers\Staff\AnnouncementController::class, 'edit'])->name('staff.announcements.edit');
    Route::put('/announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'update'])->name('staff.announcements.update');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\Staff\AnnouncementController::class, 'destroy'])->name('staff.announcements.destroy');
    
    // Staff Event Routes
    Route::get('/events', [App\Http\Controllers\Staff\EventController::class, 'index'])->name('staff.events.index');
    Route::get('/events/create', [App\Http\Controllers\Staff\EventController::class, 'create'])->name('staff.events.create');
    Route::post('/events', [App\Http\Controllers\Staff\EventController::class, 'store'])->name('staff.events.store');
    Route::get('/events/{event}', [App\Http\Controllers\Staff\EventController::class, 'show'])->name('staff.events.show');
    Route::get('/events/{event}/edit', [App\Http\Controllers\Staff\EventController::class, 'edit'])->name('staff.events.edit');
    Route::put('/events/{event}', [App\Http\Controllers\Staff\EventController::class, 'update'])->name('staff.events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\Staff\EventController::class, 'destroy'])->name('staff.events.destroy');
});

// Admin Protected Routes - These should come AFTER staff routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin routes
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/events', [App\Http\Controllers\Admin\DashboardController::class, 'getEventsForDate'])->name('admin.dashboard.events');
    
    // Admin Profile routes
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Admin Reports Routes
    Route::get('/reports/weekly', [App\Http\Controllers\Admin\ReportsController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/weekly/download/{date?}', [App\Http\Controllers\Admin\ReportsController::class, 'downloadWeekly'])->name('reports.weekly.download');
    Route::get('/reports/monthly', [App\Http\Controllers\Admin\ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/monthly/download/{date?}', [App\Http\Controllers\Admin\ReportsController::class, 'downloadMonthly'])->name('reports.monthly.download');
    Route::get('/reports/annual', [App\Http\Controllers\Admin\ReportsController::class, 'annual'])->name('reports.annual');
    Route::get('/reports/annual/download/{year?}', [App\Http\Controllers\Admin\ReportsController::class, 'downloadAnnual'])->name('reports.annual.download');
    Route::get('/reports/custom', [App\Http\Controllers\Admin\ReportsController::class, 'custom'])->name('reports.custom');
    Route::post('/reports/custom', [App\Http\Controllers\Admin\ReportsController::class, 'generateCustom'])->name('reports.custom.generate');
    Route::get('/reports/custom/download', [App\Http\Controllers\Admin\ReportsController::class, 'downloadCustom'])->name('reports.custom.download');
    
    // Admin Members Routes - Using resource route
    Route::resource('members', App\Http\Controllers\MemberController::class);
    
    // Admin Announcement Routes
    Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{id}/edit', [App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    
    // Admin Donation Routes - Using resource route
    Route::resource('donations', App\Http\Controllers\Admin\DonationController::class, ['as' => 'admin']);
    
    // Add the manual-create route separately since it's not part of the resource routes
    Route::get('/donations/manual-create', [App\Http\Controllers\Admin\DonationController::class, 'manualCreate'])->name('admin.donations.manual-create');
});

// Test route
Route::get('/test-middleware', function() {
    return 'Middleware is working correctly!';
})->middleware(['auth', 'can:admin']);

// Debug route to check user role
Route::get('/debug-role', function () {
    if (Auth::check()) {
        return 'You are logged in as: ' . Auth::user()->name . ' with role: ' . Auth::user()->role;
    } else {
        return 'You are not logged in.';
    }
});

// Debug route to check user roles in database
Route::get('/debug-users', function () {
    $users = \Illuminate\Support\Facades\DB::table('users')->get(['id', 'name', 'email', 'role']);
    return $users;
});

// Debug route to test staff authentication
Route::get('/debug-staff-auth', function () {
    $user = \Illuminate\Support\Facades\DB::table('users')->where('role', 'staff')->first();
    
    if (!$user) {
        return 'No staff user found in the database.';
    }
    
    if (\Illuminate\Support\Facades\Auth::attempt(['email' => $user->email, 'password' => 'password'])) {
        return 'Successfully authenticated as staff: ' . $user->name . ' with role: ' . $user->role;
    } else {
        return 'Failed to authenticate as staff: ' . $user->email . '. Password might be incorrect.';
    }
});

// Debug route to check user model configuration
Route::get('/debug-user-model', [App\Http\Controllers\DebugController::class, 'checkUserModel']);

// Debug route to check middleware behavior
Route::get('/debug-middleware', function (Request $request) {
    return [
        'is_authenticated' => Auth::check(),
        'user' => Auth::check() ? Auth::user() : null,
        'is_staff_login' => $request->is('staff/login'),
        'is_admin_login' => $request->is('admin/login'),
        'current_url' => $request->url(),
        'current_path' => $request->path(),
    ];
});

// Debug route for staff login
Route::get('/debug-staff-login', function () {
    return view('auth.staff.login');
})->name('debug.staff.login');

// Admin Registration Routes
Route::get('/admin/register', [App\Http\Controllers\Auth\AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [App\Http\Controllers\Auth\AdminRegisterController::class, 'register']);

// Development route to create admin user - REMOVE IN PRODUCTION
Route::get('/create-admin-user', function () {
    // Check if admin already exists
    if (\App\Models\User::where('role', 'admin')->exists()) {
        return 'Admin user already exists!';
    }
    
    // Create admin user
    $user = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    
    return 'Admin user created successfully! Email: admin@example.com, Password: password';
})->middleware('web');

// Debug route to check view paths
Route::get('/debug-views', function () {
    $viewPaths = config('view.paths');
    $viewNamespaces = app('view')->getFinder()->getHints();
    
    $adminDashboardPath = resource_path('views/admin/dashboard.blade.php');
    $adminDashboardPathCapital = resource_path('views/admin/Dashboard/dashboard.blade.php');
    
    return [
        'view_paths' => $viewPaths,
        'view_namespaces' => $viewNamespaces,
        'admin_dashboard_exists' => file_exists($adminDashboardPath),
        'admin_dashboard_capital_exists' => file_exists($adminDashboardPathCapital),
        'admin_dashboard_path' => $adminDashboardPath,
        'admin_dashboard_capital_path' => $adminDashboardPathCapital,
        'all_admin_views' => glob(resource_path('views/admin/*')),
        'all_admin_dashboard_views' => glob(resource_path('views/admin/Dashboard/*')),
    ];
});

// Simple admin dashboard route
Route::get('/admin/simple-dashboard', function () {
    return view('admin.simple-dashboard');
})->name('admin.simple-dashboard');

// Debug route to check dashboard variables
Route::get('/debug-dashboard', function (Request $request) {
    // Get timestamp from request or use current time
    $timestamp = $request->input('timestamp', now()->timestamp);
    $currentDate = \Carbon\Carbon::createFromTimestamp($timestamp);
    
    // Calculate previous and next month timestamps
    $lastMonth = $currentDate->copy()->subMonth();
    $nextMonth = $currentDate->copy()->addMonth();
    
    $lastMonthTimestamp = $lastMonth->timestamp;
    $nextMonthTimestamp = $nextMonth->timestamp;
    $todayTimestamp = \Carbon\Carbon::now()->timestamp;
    
    return [
        'currentDate' => $currentDate->format('Y-m-d'),
        'lastMonthTimestamp' => $lastMonthTimestamp,
        'nextMonthTimestamp' => $nextMonthTimestamp,
        'todayTimestamp' => $todayTimestamp,
    ];
});

// Debug route to check if EventController exists
Route::get('/debug-controller', function () {
    $controllerPath = app_path('Http/Controllers/Admin/EventController.php');
    $controllerExists = file_exists($controllerPath);
    
    $controllerClass = 'App\Http\Controllers\Admin\EventController';
    $classExists = class_exists($controllerClass);
    
    return [
        'controller_path' => $controllerPath,
        'controller_exists' => $controllerExists,
        'controller_class' => $controllerClass,
        'class_exists' => $classExists,
        'file_contents' => $controllerExists ? file_get_contents($controllerPath) : null,
    ];
});

// Debug route to check if DonationController exists
Route::get('/debug-donation-controller', function () {
    $controllerPath = app_path('Http/Controllers/Admin/DonationController.php');
    $controllerExists = file_exists($controllerPath);
    
    $controllerClass = 'App\Http\Controllers\Admin\DonationController';
    $classExists = class_exists($controllerClass);
    
    $regularControllerPath = app_path('Http/Controllers/DonationController.php');
    $regularControllerExists = file_exists($regularControllerPath);
    
    $regularControllerClass = 'App\Http\Controllers\DonationController';
    $regularClassExists = class_exists($regularControllerClass);
    
    return [
        'admin_controller_path' => $controllerPath,
        'admin_controller_exists' => $controllerExists,
        'admin_controller_class' => $controllerClass,
        'admin_class_exists' => $classExists,
        'regular_controller_path' => $regularControllerPath,
        'regular_controller_exists' => $regularControllerExists,
        'regular_controller_class' => $regularControllerClass,
        'regular_class_exists' => $regularClassExists,
    ];
});

// Debug route to check if ProfileController exists
Route::get('/debug-profile-controller', function () {
    $controllerPath = app_path('Http/Controllers/ProfileController.php');
    $controllerExists = file_exists($controllerPath);
    
    $controllerClass = 'App\Http\Controllers\ProfileController';
    $classExists = class_exists($controllerClass);
    
    $adminControllerPath = app_path('Http/Controllers/Admin/ProfileController.php');
    $adminControllerExists = file_exists($adminControllerPath);
    
    $adminControllerClass = 'App\Http\Controllers\Admin\ProfileController';
    $adminClassExists = class_exists($adminControllerClass);
    
    return [
        'controller_path' => $controllerPath,
        'controller_exists' => $controllerExists,
        'controller_class' => $controllerClass,
        'class_exists' => $classExists,
        'admin_controller_path' => $adminControllerPath,
        'admin_controller_exists' => $adminControllerExists,
        'admin_controller_class' => $adminControllerClass,
        'admin_class_exists' => $adminClassExists,
    ];
});

// Alternative approach using namespace
Route::middleware(['auth', 'admin'])->prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    // Event routes
    Route::get('/events', 'EventController@index')->name('admin.events.index');
    Route::get('/events/create', 'EventController@create')->name('admin.events.create');
    Route::post('/events', 'EventController@store')->name('admin.events.store');
    Route::get('/events/{event}', 'EventController@show')->name('admin.events.show');
    Route::get('/events/{event}/edit', 'EventController@edit')->name('admin.events.edit');
    Route::put('/events/{event}', 'EventController@update')->name('admin.events.update');
    Route::delete('/events/{event}', 'EventController@destroy')->name('admin.events.destroy');
});

// Debug route to check user role and permissions
Route::get('/debug-user', function () {
    if (!Auth::check()) {
        return [
            'status' => 'not_logged_in',
            'message' => 'User is not logged in'
        ];
    }
    
    $user = Auth::user();
    
    return [
        'status' => 'logged_in',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ],
        'can_access' => [
            'admin' => Gate::allows('admin'),
            'staff' => Gate::allows('staff'),
            'treasurer' => Gate::allows('treasurer')
        ],
        'routes' => [
            'staff_profile_exists' => Route::has('staff.profile.index'),
            'admin_profile_exists' => Route::has('profile.index')
        ]
    ];
});

// Debug route to check auth configuration
Route::get('/debug-auth', function () {
    return [
        'providers' => config('auth.providers'),
        'guards' => config('auth.guards'),
        'defaults' => config('auth.defaults'),
        'user_provider_exists' => auth()->getProvider() instanceof \App\Auth\CustomUserProvider,
        'current_user' => auth()->check() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'role' => auth()->user()->role
        ] : null
    ];
});

// Debug route to check registered routes
Route::get('/debug-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => implode(', ', $route->middleware()),
        ];
    })->filter(function ($route) {
        return str_contains($route['uri'], 'staff/events');
    })->values();
    
    return [
        'staff_event_routes' => $routes,
        'current_user' => auth()->check() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'role' => auth()->user()->role
        ] : null
    ];
});

// Debug route to check route resolution
Route::get('/debug-staff-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => implode(', ', $route->middleware()),
        ];
    })->filter(function ($route) {
        return str_contains($route['uri'], 'staff/announcements');
    })->values();
    
    return [
        'staff_announcement_routes' => $routes,
        'current_user' => auth()->check() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'role' => auth()->user()->role
        ] : null
    ];
});

// Temporary route to test if the DonationController exists and is accessible
Route::get('/test-donation-controller', function() {
    if (class_exists('App\Http\Controllers\Admin\DonationController')) {
        $controller = new App\Http\Controllers\Admin\DonationController();
        return [
            'controller_exists' => true,
            'methods' => get_class_methods($controller),
            'namespace' => (new \ReflectionClass($controller))->getNamespaceName(),
            'file_path' => (new \ReflectionClass($controller))->getFileName(),
            'file_exists' => file_exists((new \ReflectionClass($controller))->getFileName()),
        ];
    } else {
        return [
            'controller_exists' => false,
            'possible_paths' => [
                app_path('Http/Controllers/Admin/DonationController.php'),
                app_path('Http/Controllers/DonationController.php'),
            ],
            'file_exists' => [
                app_path('Http/Controllers/Admin/DonationController.php') => file_exists(app_path('Http/Controllers/Admin/DonationController.php')),
                app_path('Http/Controllers/DonationController.php') => file_exists(app_path('Http/Controllers/DonationController.php')),
            ],
            'directory_contents' => [
                'admin' => file_exists(app_path('Http/Controllers/Admin')) ? scandir(app_path('Http/Controllers/Admin')) : 'Directory does not exist',
                'controllers' => scandir(app_path('Http/Controllers')),
            ],
        ];
    }
});

// Simple route to test if route naming is working
Route::get('/admin/simple-donations', function() {
    return 'This is a simple donations page';
})->name('admin.donations.simple')->middleware(['auth', 'admin']);

// Route to test if we can access the donations index view directly
Route::get('/admin/direct-donations', function() {
    $donations = \App\Models\Donation::with(['user', 'admin'])
        ->latest()
        ->paginate(10);
    return view('admin.donations.index', compact('donations'));
})->name('admin.donations.direct')->middleware(['auth', 'admin']);

// Debug route to check if ReportsController exists
Route::get('/debug-reports-controller', function () {
    $controllerPath = app_path('Http/Controllers/Admin/ReportsController.php');
    $controllerExists = file_exists($controllerPath);
    
    $controllerClass = 'App\Http\Controllers\Admin\ReportsController';
    $classExists = class_exists($controllerClass);
    
    return [
        'controller_path' => $controllerPath,
        'controller_exists' => $controllerExists,
        'controller_class' => $controllerClass,
        'class_exists' => $classExists,
        'file_contents' => $controllerExists ? file_get_contents($controllerPath) : null,
    ];
});

















