<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $adoptions = Adoption::where('customer_id', $user->id)->get();
        
        return view('user.profile', compact('user', 'adoptions'));
    }

    public function showSettings()
    {
        return view('user.editData');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'email' => 'required|email|max:100|unique:users,email,' . Auth::id(),
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Name is required.',
            'name.alpha' => 'Name can only contain letters.',
            'name.max' => 'Name can be at most 20 characters.',
            'last_name.required' => 'Last Name is required.',
            'last_name.alpha' => 'Last Name can only contain letters.',
            'last_name.max' => 'Last Name can be at most 30 characters.',
            'email.required' => 'E-mail is required.',
            'email.email' => 'Please provide a valid E-mail address.',
            'email.unique' => 'This E-mail is already in use.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number can only contain digits.',
            'phone_number.max' => 'Phone number can be at most 15 digits.',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.confirmed' => 'Passwords do not match.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password changed successfully.');
    }
}
