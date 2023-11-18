<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        //flood protection
        $this->middleware('throttle:' . config('auth.throttles.verify'))->only('verify','resend');
    }

    /**
     * Display an email verification notice
     *
     * @return View
     */
    public function notice(Request $request): View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('home') : view('auth.verify-email');
    }

    /**
     * Email verification
     *
     * @param  EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();
        return redirect()->route('home');
    }

    /**
     * Resend the verification email
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();
        return back()
            ->withSuccess(__('auth.resend'));
    }

}