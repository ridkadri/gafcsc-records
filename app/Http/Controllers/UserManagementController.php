<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $availableRoles = $this->getAvailableRoles();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in($availableRoles)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} created successfully with {$user->getRoleDisplayName()} role.");
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->canEdit($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->canEdit($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }

        $availableRoles = $this->getAvailableRoles();
        $request->validate([
            'role' => ['required', Rule::in($availableRoles)],
        ]);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot change your own role.');
        }

        if ($request->role === User::ROLE_SUPER_ADMIN && !auth()->user()->isSuperAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Only Super Administrators can assign Super Administrator roles.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->route('users.index')
            ->with('success', "User {$user->name}'s role updated to {$user->getRoleDisplayName()}.");
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->canDelete($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to delete this user.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$userName} has been deleted successfully.");
    }

    public function showResetForm(User $user)
    {
        if (!auth()->user()->canResetPassword($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to reset this user\'s password.');
        }
        return view('users.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        if (!auth()->user()->canResetPassword($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to reset this user\'s password.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('users.index')
            ->with('success', "Password reset successfully for {$user->name}.");
    }

    // Initial setup methods
    public function makeAdmin()
    {
        auth()->user()->update(['role' => User::ROLE_ADMIN]);
        return redirect()->route('dashboard')->with('success', 'You are now an administrator!');
    }

    public function makeSuperAdmin()
    {
        if (User::where('role', User::ROLE_SUPER_ADMIN)->exists()) {
            return redirect()->route('dashboard')->with('error', 'A Super Administrator already exists.');
        }
        
        auth()->user()->update(['role' => User::ROLE_SUPER_ADMIN]);
        return redirect()->route('dashboard')->with('success', 'You are now the Super Administrator!');
    }

    // Helper methods
    private function getAvailableRoles(): array
    {
        $currentUser = auth()->user();
        
        if ($currentUser->isSuperAdmin()) {
            return [User::ROLE_ADMIN, User::ROLE_VIEWER, User::ROLE_SUPER_ADMIN];
        } elseif ($currentUser->isAdmin()) {
            return [User::ROLE_ADMIN, User::ROLE_VIEWER];
        }
        
        return [User::ROLE_VIEWER];
    }
}