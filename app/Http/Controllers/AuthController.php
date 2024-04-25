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

    $sql = "INSERT INTO users (name, email, password_hash, phone_number, created_at, deleted_at) VALUES (?, ?, ?, ?, NOW(), NULL)";
    $bindings = [
        $validatedData['name'],
        $validatedData['email'],
        Hash::make($validatedData['password']),
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

    $sql = "SELECT * FROM users WHERE email = ?";
    $user = DB::select($sql, [$credentials['email']])[0] ?? null;

    if (!$user) {
        return back()->withErrors(['email' => 'No user found with that email address']);
    }

    if ($user && Hash::check($credentials['password'], $user->password_hash)) {
        // Manually logging in the user
        Auth::loginUsingId($user->user_id);  // Ensure you use the correct attribute for user ID

        // Redirect to the home page after successful login
        return redirect('/')->with('success', 'Login successful');
    } else {
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
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
