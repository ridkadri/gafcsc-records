<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user's role.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,viewer',
        ]);

        // Prevent users from changing their own role
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot change your own role.');
        }

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name}'s role updated to {$request->role}.");
    }

    /**
     * Make current user admin (for initial setup)
     */
    public function makeAdmin()
    {
        $user = auth()->user();
        $user->update(['role' => 'admin']);
        
        return redirect()->route('dashboard')
            ->with('success', 'You are now an administrator!');
    }
}