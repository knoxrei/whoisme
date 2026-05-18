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

        $user = User::create([
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'password' => $validateData['password'],
            'last_active' => now(),
        ]);

        // Create identification record automatically
        $user->identification()->create([
            'role' => Role::MEMBER, // Default role
        ]);

if($user){

        return redirect()->route('login')->with('success', 'Account has been created, please login');
};

        return redirect()->route('register')->with('error', 'Account creation failed!');

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
            // dont forget to session replace 

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
