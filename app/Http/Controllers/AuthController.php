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
    // Validate the input fields
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|min:6',
        'phone_number' => 'nullable|max:15'
    ]);

    // Check if the email already exists in the database
    $existingUser = DB::select('SELECT * FROM users WHERE email = ?', [$validatedData['email']]);

    // If the email exists, redirect back with an error message
    if (!empty($existingUser)) {
        return back()->withErrors(['email' => 'This email address is already registered.'])->withInput($request->except('password'));
    }

    // If the email does not exist, proceed with registration
    $sql = "INSERT INTO users (name, email, password, phone_number, created_at, deleted_at) VALUES (?, ?, ?, ?, NOW(), NULL)";
    $bindings = [
        $validatedData['name'],
        $validatedData['email'],
        Hash::make($validatedData['password']),
        $validatedData['phone_number']
    ];

    DB::insert($sql, $bindings);

    // Redirect to login page with a success message
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


    public function checkEmail(Request $request)
{
    $validatedData = $request->validate([
        'email' => 'required|email'
    ]);

    $user = DB::select("SELECT * FROM users WHERE email = ?", [$validatedData['email']]);

    if (empty($user)) {
        return back()->withErrors(['email' => 'No account found with that email address'])->withInput();
    }

    // Store the email in the session to use in the next view
    session(['email' => $validatedData['email']]);

    // Redirect to a view where the user can reset their password
    return view('reset');
}



// Add this method to AuthController
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed'
    ]);

    // Update password in the database
    DB::statement("UPDATE users SET password = ? WHERE email = ?", [Hash::make($request->password), $request->email]);

    // Redirect to the login page with a success message
    return redirect('/login')->with('success', 'Your password has been reset successfully!');
}



    
}
