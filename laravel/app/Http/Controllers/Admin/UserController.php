<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDataRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function update(UserDataRequest $request, User $user)
    {
        $makeAdmin = $request->boolean('admin');

        if ($makeAdmin && ! $user->isAdmin() && $user->adoptions()->exists()) {
            return back()->with('error', 'Cannot assign admin role to a user with existing adoptions.');
        }

        // Admins cannot demote or delete themselves, so the acting admin always remains
        // and the last admin can never be removed.
        if (! $makeAdmin && $user->is($request->user())) {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        $user->update([...$request->validated(), 'role' => $makeAdmin ? 'admin' : 'customer']);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
