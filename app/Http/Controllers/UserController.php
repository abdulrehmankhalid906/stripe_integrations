<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerStore(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        User::create($data);
        
        return redirect()->route('user.login')->with('success', 'User registered successfully.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function verifyingUser(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'This email does not exist.');
        }
    
        if (!Hash::check($data['password'], $user->password)) {
            return redirect()->back()->with('error', 'Your password does not match.');
        }
    
        Auth::login($user);
    
        return redirect()->route('user.dashboard')->with('success', 'User logged in successfully.');
    }

    public function dashboard()
    {
        return view('dashboard.home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'User logged out successfully.');
    }
}
