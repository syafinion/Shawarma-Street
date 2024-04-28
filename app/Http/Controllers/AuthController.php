<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); 
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'phone_number' => 'nullable|max:15'
        ]);

        // Updated to use 'password' instead of 'password_hash'
        $sql = "INSERT INTO users (name, email, password, phone_number, created_at, deleted_at) VALUES (?, ?, ?, ?, NOW(), NULL)";
        $bindings = [
            $validatedData['name'],
            $validatedData['email'],
            Hash::make($validatedData['password']),  // Hashing the password
            $validatedData['phone_number']
        ];

        DB::insert($sql, $bindings);

        return redirect('/login')->with('success', 'Registration successful. You can now login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // First, attempt to authenticate using Laravel's built-in functionality
        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to homepage with success message
            return redirect('/')->with('success', 'Login successful');
        }

        // If Auth::attempt fails, manually verify if user exists to provide specific feedback
        $user = DB::table('users')->where('email', $credentials['email'])->first();

        // Check if user was found
        if (!$user) {
            // No user found with that email address
            return back()->withErrors(['email' => 'No user found with that email address'])->withInput($request->only('email'));
        } else {
            // User exists but password is incorrect
            return back()->withErrors(['password' => 'Incorrect password provided'])->withInput($request->only('email'));
        }
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
