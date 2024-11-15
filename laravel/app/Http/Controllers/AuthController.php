<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            logger('User is already logged in.');
            return redirect()->route('index'); 
        }
        return view('auth.login');
    }

    public function authenticate(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ]);

        $remember = $request->has('remember'); 

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('user/profile');
        } else {
            return back()->withErrors(['email' => 'Invalid login credentials.'])->onlyInput('email');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ],
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Name is required.',
            'last_name.required' => 'Last Name is required.',
            'email.required' => 'E-mail is required.',
            'email.email' => 'Please provide a valid E-mail address.',
            'email.unique' => 'This E-mail is already in use.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, digit, and special character.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number can only contain digits.',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['phone_number'],
            'role' => 'customer'
        ]);

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Registration successful!');
    }
}
