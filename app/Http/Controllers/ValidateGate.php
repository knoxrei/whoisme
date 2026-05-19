<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateGate extends Controller
{
    public function register()
    {
        $title = 'Register';
        return view('gate.register', compact('title'));
    }

    public function registerStore(RegisterRequest $request)
    {
        $validateData = $request->validated();

        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        // Store registration info in session instead of database
        session()->put('pending_registration', [
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'password' => $validateData['password'],
            'verification_code' => $otpCode,
            'verification_expires_at' => now()->addMinutes(15)->toIso8601String(),
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
            'verification_expires_at' => null
        ]);

        // Create identification record automatically
        $user->identification()->create([
            'role' => Role::MEMBER, // Default role
        ]);

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
        return view('gate.login', compact('title'));
    }

    public function loginStore(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt($validatedData)) {
            session()->put('anonuser', Auth::user()->username);
            session()->flash('success', "Data {$validatedData['username']} has been logged in!");
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->with('error', "Data its not in our record!");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flash('success', "You have been logged out!");
        return redirect()->route('login');
    }
}
