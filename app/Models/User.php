<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_VIEWER = 'viewer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role validation on save
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($user) {
            $validRoles = [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_VIEWER];
            if (!in_array($user->role, $validRoles)) {
                throw new \InvalidArgumentException("Invalid role: {$user->role}");
            }
        });
    }

    // Role checks
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    public function hasAdminPrivileges(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    // Staff and system permissions
    public function canManageStaff(): bool
    {
        return $this->hasAdminPrivileges();
    }

    public function canViewStaff(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_VIEWER, self::ROLE_SUPER_ADMIN]);
    }

    public function canManageUsers(): bool
    {
        return $this->hasAdminPrivileges();
    }

    public function canManageSuperAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    // User permission checks
    public function canEdit(User $targetUser): bool
    {
        if ($this->isSuperAdmin()) {
            return !$targetUser->isSuperAdmin() || $this->id === $targetUser->id;
        }
        if ($this->isAdmin()) {
            return !$targetUser->isSuperAdmin() && $this->id !== $targetUser->id;
        }
        return false;
    }

    public function canDelete(User $targetUser): bool
    {
        if ($this->id === $targetUser->id) return false;
        if ($targetUser->isSuperAdmin()) return $this->isSuperAdmin();
        return $this->hasAdminPrivileges();
    }

    public function canResetPassword(User $targetUser): bool
    {
        if ($this->isSuperAdmin()) return !$targetUser->isSuperAdmin();
        if ($this->isAdmin()) return !$targetUser->isSuperAdmin();
        return false;
    }

    // Helper methods
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrator',
            self::ROLE_ADMIN => 'Administrator', 
            self::ROLE_VIEWER => 'Viewer',
            default => 'Unknown'
        };
    }

    // Scopes
    public function scopeSuperAdmins($query)
    {
        return $query->where('role', self::ROLE_SUPER_ADMIN);
    }

    public function scopeAdminLevel($query)
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }
}