<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\PasswordVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateGate extends Controller
{
    public function register(Request $request)
    {
        $ref = $request->query('ref');

        if (!$ref && session()->has('referrer_id')) {
            $referrer = User::find(session()->get('referrer_id'));
            if ($referrer) {
                $ref = $referrer->username;
            }
        }

        if ($ref) {
            $referrer = User::where('username', $ref)->first();
            if ($referrer) {
                session()->put('referrer_id', $referrer->id);
            }
        }

        $title = 'Register';
        return view('gate.register', compact('title', 'ref'));
    }

    public function registerStore(RegisterRequest $request)
    {
        $validateData = $request->validated();

        // Determine referrer from request or session
        $referrerId = null;
        if (!empty($validateData['ref'])) {
            $referrer = User::where('username', $validateData['ref'])->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        if (!$referrerId) {
            $referrerId = session()->get('referrer_id');
        }

        // ── If NO email provided: create account directly (no OTP) ────────
        if (empty($validateData['email'])) {
            $user = User::create([
                'username'                => $validateData['username'],
                'email'                   => null,
                'password'                => $validateData['password'],
                'last_active'             => now(),
                'email_verified_at'       => null,
                'verification_code'       => null,
                'verification_expires_at' => null,
                'referred_by'             => $referrerId,
            ]);

            $user->identification()->create([
                'role'       => \App\Enum\Role::MEMBER,
                'reputation' => $referrerId ? 10 : 0,
            ]);

            if ($referrerId) {
                $referrer = User::find($referrerId);
                if ($referrer && $referrer->identification) {
                    $referrer->identification->increment('reputation', 10);
                }
                session()->forget('referrer_id');
            }

            return redirect()->route('login')->with('success', 'Account created successfully! You may now login.');
        }

        // ── If email provided: send OTP for verification ──────────────────
        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        session()->put('pending_registration', [
            'username'                => $validateData['username'],
            'email'                   => $validateData['email'],
            'password'                => $validateData['password'],
            'verification_code'       => $otpCode,
            'verification_expires_at' => now()->addMinutes(15)->toIso8601String(),
            'referrer_id'             => $referrerId,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($validateData['email'])->queue(new \App\Mail\VerifyEmailMail($validateData['username'], $otpCode));
            return redirect()->route('verify.registration.show')->with('success', 'Security key has been routed to your email address.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP mail routing failure: ' . $e->getMessage());
            return redirect()->route('verify.registration.show')->with('warning', 'Mail routing failed, but registration is held. Code logged internally: ' . $otpCode);
        }
    }

    public function verifyRegistrationShow()
    {
        $pending = session()->get('pending_registration');
        if (!$pending) {
            return redirect()->route('register.index')->with('error', 'No pending registration session found.');
        }

        $title = 'Verify Registration';
        return view('gate.verify-email', compact('title', 'pending'));
    }

    public function verifyRegistrationPost(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $pending = session()->get('pending_registration');
        if (!$pending) {
            return redirect()->route('register.index')->with('error', 'No pending registration session found.');
        }

        if ($pending['verification_code'] !== $request->code) {
            return back()->with('error', 'Security OTP code is incorrect or invalid.');
        }

        $expiry = \Carbon\Carbon::parse($pending['verification_expires_at']);
        if (now()->gt($expiry)) {
            return back()->with('error', 'Security OTP code has expired. Please request a new code.');
        }

        // OTP verified! Create user account in database
        $user = User::create([
            'username' => $pending['username'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'last_active' => now(),
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_expires_at' => null,
            'referred_by' => $pending['referrer_id'] ?? null,
        ]);

        // Create identification record automatically
        $user->identification()->create([
            'role' => Role::MEMBER, // Default role
            'reputation' => isset($pending['referrer_id']) ? 10 : 0, // 10 reputation if referred!
        ]);

        // Award 10 reputation points to the referrer
        if (isset($pending['referrer_id'])) {
            $referrer = User::find($pending['referrer_id']);
            if ($referrer && $referrer->identification) {
                $referrer->identification->increment('reputation', 10);
            }
            // Clear the session referrer after usage
            session()->forget('referrer_id');
        }

        // Clear session data
        session()->forget('pending_registration');

        return redirect()->route('login')->with('success', 'Account created and verified successfully! You may now login.');
    }

    public function verifyRegistrationResend()
    {
        $pending = session()->get('pending_registration');
        if (!$pending) {
            return redirect()->route('register.index')->with('error', 'No pending registration session found.');
        }

        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        // Update verification details in session
        $pending['verification_code'] = $otpCode;
        $pending['verification_expires_at'] = now()->addMinutes(15)->toIso8601String();
        session()->put('pending_registration', $pending);

        try {
            \Illuminate\Support\Facades\Mail::to($pending['email'])->queue(new \App\Mail\VerifyEmailMail($pending['username'], $otpCode));
            return back()->with('success', 'A new dynamic security key has been routed to your email.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP mail routing failure: ' . $e->getMessage());
            return back()->with('warning', 'Mail routing failed. New code logged internally: ' . $otpCode);
        }
    }

    public function login()
    {
        $title = 'Login';
        $legacyPlatformName = config('platform.legacy_name');

        return view('gate.login', compact('title', 'legacyPlatformName'));
    }

    public function loginStore(LoginRequest $request, PasswordVerifier $passwordVerifier)
    {
        $validatedData = $request->validated();
        $identity = $validatedData['username'];
        $password = $validatedData['password'];

        $user = User::query()
            ->where('username', $identity)
            ->orWhere('email', $identity)
            ->first();

        if (! $user || ! $passwordVerifier->verify($user, $password)) {
            return redirect()->route('login')->with('error', 'Credentials do not match our records.');
        }

        Auth::login($user);
        $user->update(['last_active' => now()]);

        session()->put('anonuser', $user->username);
        session()->put('login_time', now());
        \Illuminate\Support\Facades\Cache::put("user:login_time:{$user->id}", now(), now()->addHours(2));
        session()->flash('success', "Welcome back, {$user->username}!");

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flash('success', "You have been logged out!");
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        $title = 'Forgot Password';
        return view('gate.forgot-password', compact('title'));
    }

    public function forgotPasswordStore(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
        ]);

        $identity = $request->input('identity');

        // Look up by username or email
        $user = User::where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if (!$user) {
            return back()->with('error', 'No matching account was found with those credentials.');
        }

        // Generate 6-digit password reset OTP code
        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        // Store reset details in session
        session()->put('pending_password_reset', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verification_code' => $otpCode,
            'verification_expires_at' => now()->addMinutes(15)->toIso8601String(),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\ResetPasswordMail($user->username, $otpCode));
            return redirect()->route('password.reset')->with('success', 'Clearance key has been routed to the associated email.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Password reset SMTP failure: ' . $e->getMessage());
            return redirect()->route('password.reset')->with('warning', 'Mail routing failed. Code logged internally: ' . $otpCode);
        }
    }

    public function resetPassword()
    {
        $pending = session()->get('pending_password_reset');
        if (!$pending) {
            return redirect()->route('password.request')->with('error', 'No active password reset session was found.');
        }

        $title = 'Reset Password';
        $email = $pending['email'];
        return view('gate.reset-password', compact('title', 'email'));
    }

    public function resetPasswordStore(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $pending = session()->get('pending_password_reset');
        if (!$pending) {
            return redirect()->route('password.request')->with('error', 'No active password reset session was found.');
        }

        if ($pending['verification_code'] !== $request->code) {
            return back()->with('error', 'Clearance OTP code is incorrect or invalid.');
        }

        $expiry = \Carbon\Carbon::parse($pending['verification_expires_at']);
        if (now()->gt($expiry)) {
            return back()->with('error', 'Clearance OTP code has expired. Please request a new code.');
        }

        // Retrieve the user and update password
        $user = User::find($pending['user_id']);
        if (!$user) {
            return redirect()->route('password.request')->with('error', 'User account could not be found.');
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        // Clear password reset session
        session()->forget('pending_password_reset');

        return redirect()->route('login')->with('success', 'Password reset successfully! You may now login with your new credentials.');
    }

    // ── Email Verification from Settings ─────────────────────────────────

    /**
     * Send an email verification OTP from profile settings.
     * Supports: adding a new email or re-verifying an existing one.
     */
    public function sendEmailVerification(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $email   = $request->email;
        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        session()->put('pending_email_verification', [
            'user_id'                 => $user->id,
            'email'                   => $email,
            'verification_code'       => $otpCode,
            'verification_expires_at' => now()->addMinutes(15)->toIso8601String(),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($email)->queue(new \App\Mail\VerifyEmailMail($user->username, $otpCode));
            return redirect()->route('profile.edit')
                ->with('success', 'Verification code sent to ' . $email . '. Enter the 6-digit code below.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email verify SMTP failure: ' . $e->getMessage());
            return redirect()->route('profile.edit')
                ->with('warning', 'Mail routing failed. Code logged internally: ' . $otpCode);
        }
    }

    /**
     * Confirm the OTP code submitted from profile settings.
     */
    public function confirmEmailVerification(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $pending = session()->get('pending_email_verification');

        if (!$pending || $pending['user_id'] !== auth()->id()) {
            return redirect()->route('profile.edit')->with('error', 'No pending email verification found.');
        }

        if ($pending['verification_code'] !== $request->code) {
            return back()->with('error', 'Verification code is incorrect.');
        }

        $expiry = \Carbon\Carbon::parse($pending['verification_expires_at']);
        if (now()->gt($expiry)) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        auth()->user()->update([
            'email'             => $pending['email'],
            'email_verified_at' => now(),
        ]);

        session()->forget('pending_email_verification');

        return redirect()->route('profile.edit')
            ->with('success', '✓ Email verified successfully!');
    }
}
