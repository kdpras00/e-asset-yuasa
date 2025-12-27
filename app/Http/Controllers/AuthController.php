<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:tim_faxed_asset,inventory,karyawan,pimpinan,hrd',
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        return redirect('/assets');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Forgot Password & OTP
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Mock OTP Logic
        $otp = rand(100000, 999999);
        $request->session()->put('otp', $otp);
        $request->session()->put('otp_email', $request->email);

        // In real app: Mail::to($request->email)->send(new OtpMail($otp));
        
        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email (Mock: ' . $otp . ')');
    }

    public function showVerifyOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|array|min:6']);
        $inputOtp = implode('', $request->otp);
        
        $sessionOtp = $request->session()->get('otp');
        $email = $request->session()->get('otp_email');

        if ($inputOtp == $sessionOtp && $email) {
            // Login user directly for this demo
            $user = User::where('email', $email)->first();
            Auth::login($user);
            
            $request->session()->forget(['otp', 'otp_email']);
            
            return redirect('/assets');
        }

        return back()->with('error', 'Invalid OTP');
    }
}
