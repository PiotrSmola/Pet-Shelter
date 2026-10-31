<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid login credentials.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('user.profile'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Name is required.',
            'last_name.required' => 'Last Name is required.',
            'email.required' => 'E-mail is required.',
            'email.email' => 'Please provide a valid E-mail address.',
            'email.unique' => 'This E-mail is already in use.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number can only contain digits.',
        ]);

        $user = User::create([...$data, 'role' => 'customer']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('index')->with('success', 'Registration successful!');
    }
}
