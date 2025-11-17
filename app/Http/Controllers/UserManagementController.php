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
        
        // Group users by role for better display
        $groupedUsers = [
            'super_admin' => $users->where('role', User::ROLE_SUPER_ADMIN),
            'military_admin' => $users->where('role', User::ROLE_MILITARY_ADMIN)->merge($users->where('role', 'admin')),
            'chief_clerk' => $users->where('role', User::ROLE_CHIEF_CLERK),
            'capo' => $users->where('role', User::ROLE_CAPO),
            'peo' => $users->where('role', User::ROLE_PEO),
            'viewer' => $users->where('role', User::ROLE_VIEWER),
        ];
        
        return view('users.index', compact('users', 'groupedUsers'));
    }

    public function create()
    {
        $availableRoles = $this->getAvailableRolesForSelect();
        return view('users.create', compact('availableRoles'));
    }

    public function store(Request $request)
    {
        $availableRoles = array_keys($this->getAvailableRolesForSelect());
        
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
        
        $availableRoles = $this->getAvailableRolesForSelect();
        return view('users.edit', compact('user', 'availableRoles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->canEdit($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }

        $availableRoles = array_keys($this->getAvailableRolesForSelect());
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

        $oldRole = $user->getRoleDisplayName();
        $user->update(['role' => $request->role]);
        $newRole = $user->getRoleDisplayName();

        return redirect()->route('users.index')
            ->with('success', "User {$user->name}'s role updated from {$oldRole} to {$newRole}.");
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

        // Reset to default password
    $user->resetToDefaultPassword();

    return redirect()->route('users.index')
        ->with('success', "Password reset successfully for {$user->name}. Default password (gafcsc@123) has been set and user will be required to change it on next login.");
    }

    // Add a new method for quick reset (without form)
    public function quickResetPassword(User $user)
    {
        if (!auth()->user()->canResetPassword($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to reset this user\'s password.');
        }

        // Reset to default password
        $user->resetToDefaultPassword();

        return redirect()->route('users.index')
            ->with('success', "Password reset successfully for {$user->name}. Default password (gafcsc@123) has been set.");

    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user->update(['password' => Hash::make($request->password)]);

    return redirect()->route('users.index')
        ->with('success', "Password reset successfully for {$user->name}.");
    }

    // Helper methods
    private function getAvailableRolesForSelect(): array
    {
        $currentUser = auth()->user();
        
        if ($currentUser->isSuperAdmin()) {
            return User::getAllRoles();
        }
        
        // Non-super admins can't assign roles
        return [];
    }
}