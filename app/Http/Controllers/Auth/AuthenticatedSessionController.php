<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Services\Crypto\CustomHash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login form
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login — Step 1: verify password
     * If valid → send OTP → redirect to OTP page
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 1. Find user by email_hash
        $emailHash = (new CustomHash())->quickHash($request->email);
        $user = User::where('email_hash', $emailHash)->first();

        // 2. Also try finding by plain email (for backward compat)
        if (!$user) {
            $user = User::where('email', $request->email)->first();
        }

        // 3. Verify password using CustomHash
        $valid = false;

        if ($user && $user->password_salt && $user->password_custom) {
            // Use our custom hash verification
            $hasher = new CustomHash();
            $valid  = $hasher->verify($request->password, $user->password_custom, $user->password_salt);
        } elseif ($user) {
            // Fallback: existing users before crypto migration
            $valid = \Hash::check($request->password, $user->password);
        }

        if (!$valid || !$user) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        // 4. Generate OTP (6 digits)
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // 5. Save OTP to user record
        $user->update([
            'otp_code'        => $otp,
            'otp_expires_at'  => now()->addMinutes(10),
            'two_fa_verified' => false,
        ]);

        // 6. Send OTP via email
        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        // 7. Store user_id in session for OTP verification step
        $request->session()->put('2fa_user_id', $user->id);
        $request->session()->put('2fa_remember', $request->boolean('remember'));

        return redirect()->route('two-factor.show')
                         ->with('status', 'Verification code sent to your email.');
    }

    /**
     * Show OTP verification form
     */
    public function showTwoFactor(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    /**
     * Handle OTP verification — Step 2: verify OTP
     */
    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('2fa_user_id');

        if (!$userId) {
            return redirect()->route('login')
                             ->withErrors(['otp' => 'Session expired. Please login again.']);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Verify OTP
        if (!$user->isOtpValid($request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // 2FA passed — clear OTP, mark verified
        $user->update([
            'otp_code'        => null,
            'otp_expires_at'  => null,
            'two_fa_verified' => true,
        ]);

        // Clear 2FA session data
        $request->session()->forget(['2fa_user_id', '2fa_remember']);

        // Login user
        Auth::login($user, false);
        $request->session()->regenerate();

        // Secure session fingerprint
        $request->session()->put('user_agent_hash',
            hash('sha256', $request->userAgent() ?? '')
        );
        $request->session()->put('ip_hash',
            hash('sha256', $request->ip() ?? '')
        );

        return redirect()->intended(
            route(auth()->user()->getRedirectRouteName(), absolute: false)
        );
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('success', 'You are logged out successfully!');

        return redirect('/');
    }
}

