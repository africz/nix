<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;



class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'home'
        ]);
        $this->middleware('auth')->only('logout', 'home');
        $this->middleware('verified')->only('home');
        //flooding protection
        $this->middleware('throttle:' . config('auth.throttles.login'))->only('login');
        $this->middleware('throttle:' . config('auth.throttles.register'))->only('register');
    }

    /**
     * Display a registration form.
     *
     * @return View
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  RegisterRequest  $request
     * @return RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'agreement' => $request->agreement,
        ]);

        event(new Registered($user));

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('verification.notice');
    }

    /**
     * Display login form.
     *
     * @return View
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate a user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');

    }

    /**
     * Display home to authenticated users.
     *
     * @return View
     */
    public function home(): View
    {
        return view('auth.home');
    }

    /**
     * Log out the user from application.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess(__('auth.loggedOut'));
    }

}
