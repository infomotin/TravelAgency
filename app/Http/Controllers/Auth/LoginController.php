<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $status = 'failed';
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $status = 'success';
            LoginActivity::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $status,
            ]);
            return redirect()->intended('/admin');
        }
        LoginActivity::create([
            'user_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $status,
        ]);
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
