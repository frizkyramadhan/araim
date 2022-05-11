<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'Login';
        $subtitle = 'ARAIM v2.0';
        return view('login', compact('title','subtitle'));
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email:dns|ends_with:@arka.co.id',
            'password' => 'required|min:5',
        ],[
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);

        if(Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password'], 'user_status' => 1])){
            $request->session()->regenerate();
            return redirect()->intended('/');
        } else {
            return back()->with('loginError', 'Login failed!');
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('login');
    }
}
