<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $title = 'Register';
        $subtitle = 'ARAIM v2.0';
        $projects = Project::where('project_status', '=', '1')->orderBy('project_code', 'asc')->get();

        return view('register', compact('title', 'subtitle', 'projects'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|ends_with:@arka.co.id|unique:users',
            'password' => 'required|min:5',
            'level' => 'required',
            'project_id' => 'required',
            'user_status' => 'required',
        ], [
            'name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect('login')->with('success', 'Registration success! Please contact IT to activate your account.');
    }
}
