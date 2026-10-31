<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDataRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $adoptions = $user->adoptions()->with('pet')->latest()->latest('id')->get();

        return view('user.profile', compact('user', 'adoptions'));
    }

    public function edit(Request $request)
    {
        return view('user.edit', ['user' => $request->user()]);
    }

    public function update(UserDataRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'Current password does not match.',
            'new_password.required' => 'New password is required.',
            'new_password.confirmed' => 'Passwords do not match.',
        ]);

        $request->user()->update(['password' => $request->new_password]);

        return redirect()->route('user.profile')->with('success', 'Password changed successfully.');
    }
}
