<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Policies\RegistrationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * The registration policy instance.
     *
     * @var \App\Policies\RegistrationPolicy
     */
    protected $registrationPolicy;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Policies\RegistrationPolicy  $registrationPolicy
     * @return void
     */
    public function __construct(RegistrationPolicy $registrationPolicy)
    {
        $this->registrationPolicy = $registrationPolicy;
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        // Check if registration is allowed
        if (!$this->registrationPolicy->register(Auth::user())) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Registration is not allowed at this time.');
        }

        return view('auth.register');
    }

    /**
     * Handle the registration request.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request)
    {
        // Check if registration is allowed
        if (!$this->registrationPolicy->register(Auth::user())) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Registration is not allowed at this time.');
        }

        // Check if the user can register with the specified position
        if (!$this->registrationPolicy->registerWithPosition(Auth::user(), $request->position)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You do not have permission to register with this position.');
        }

        // Create the user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('member.dashboard')
            ->with('success', 'Registration successful! Welcome to GKCFaithLink.');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update basic profile information
        $user->name = $request->name;
        $user->contact_number = $request->contact_number;
        $user->address = $request->address;

        // Handle password change if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Current password is incorrect.')
                    ->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile-images', 'public');
            $user->image_path = $path;
        }

        $user->save();

        return redirect()->back()
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the member profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('member.profile', compact('user'));
    }
}