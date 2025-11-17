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
    const ROLE_MILITARY_ADMIN = 'military_admin';
    const ROLE_HOD = 'head_of_department';
    const ROLE_CHIEF_CLERK = 'chief_clerk';
    const ROLE_CAPO = 'capo';
    const ROLE_PEO = 'peo';
    const ROLE_VIEWER = 'viewer';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'date_of_birth',
        'staff_id',
        'profile_picture',
        'role',
        'password_changed_at',
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
            'date_of_birth' => 'date',
            'password_changed_at' => 'datetime',
        ];
    }

    public function needsPasswordChange(): bool
    {
        return is_null($this->password_changed_at);
    }

    // FIX: Add HOD to valid roles
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($user) {
            $validRoles = [
                self::ROLE_SUPER_ADMIN,
                self::ROLE_ADMIN,
                self::ROLE_MILITARY_ADMIN,
                self::ROLE_HOD, // ADD THIS LINE
                self::ROLE_CHIEF_CLERK,
                self::ROLE_CAPO,
                self::ROLE_PEO,
                self::ROLE_VIEWER,
            ];
            
            if (!in_array($user->role, $validRoles)) {
                throw new \InvalidArgumentException("Invalid role: {$user->role}");
            }
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // ==================== ROLE CHECKS ====================
    
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMilitaryAdmin(): bool
    {
        return $this->role === self::ROLE_MILITARY_ADMIN;
    }

    public function isHod(): bool
    {
        return $this->role === self::ROLE_HOD || ($this->staff && $this->staff->is_hod);
    }

    public function isChiefClerk(): bool
    {
        return $this->role === self::ROLE_CHIEF_CLERK;
    }

    public function isCapo(): bool
    {
        return $this->role === self::ROLE_CAPO;
    }

    public function isPeo(): bool
    {
        return $this->role === self::ROLE_PEO;
    }

    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    public function hasAdminPrivileges(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isMilitaryAdmin();
    }

    // ==================== HOD METHODS ====================

    public function isHeadOfDepartment(): bool
    {
        return $this->isHod() && $this->staff && $this->staff->department;
    }

    public function getManagedDepartment(): ?string
    {
        if ($this->isHeadOfDepartment() && $this->staff) {
            return $this->staff->department;
        }
        return null;
    }

    public function getSubordinates()
    {
        if (!$this->isHeadOfDepartment() || !$this->staff) {
            return collect();
        }
        
        return $this->staff->subordinates;
    }

    public function getSubordinatesCount(): int
    {
        return $this->getSubordinates()->count();
    }

    // ==================== STAFF PERMISSIONS ====================
    
    public function canViewStaff(): bool
    {
        return $this->canViewMilitaryStaff() || $this->canViewCivilianStaff() || $this->isHod();
    }

    // Add method to check if user can be both Admin and HOD
    public function canHaveMultipleRoles(): bool
    {
        // Allow these roles to also be HODs
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_MILITARY_ADMIN,
        ]);
    }

    public function canViewAllStaff(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isMilitaryAdmin();
    }

    public function canViewMilitaryStaff(): bool
    {
        return $this->isSuperAdmin()
            || $this->isAdmin() 
            || $this->isMilitaryAdmin() 
            || $this->isChiefClerk();
    }

    public function canViewCivilianStaff(): bool
    {
        return $this->isSuperAdmin()
            || $this->isAdmin() 
            || $this->isMilitaryAdmin() 
            || $this->isCapo() 
            || $this->isPeo();
    }

    public function canAddMilitaryStaff(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isChiefClerk();
    }

    public function canEditMilitaryStaff(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isChiefClerk();
    }

    public function canAddCivilianStaff(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isPeo() || $this->isCapo();
    }

    public function canEditCivilianStaff(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isCapo();
    }

    public function canManageStaff(): bool
    {
        return $this->isSuperAdmin() 
            || $this->isAdmin()
            || $this->isChiefClerk() 
            || $this->isCapo() 
            || $this->isPeo();
    }

    public function canViewInventory(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isMilitaryAdmin();
    }

    public function canManageInventory(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canManageSuperAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    // ==================== USER EDIT/DELETE PERMISSIONS ====================
    
    public function canEdit(User $targetUser): bool
    {
        if ($this->isSuperAdmin()) {
            return !$targetUser->isSuperAdmin() || $this->id === $targetUser->id;
        }

        if ($this->isAdmin()) {
            return !$targetUser->isSuperAdmin();
        }
        
        return false;
    }

    public function canDelete(User $targetUser): bool
    {
        if ($this->id === $targetUser->id) {
            return false;
        }
        
        if ($targetUser->isSuperAdmin()) {
            return false;
        }
        
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canResetPassword(User $targetUser): bool
    {
        if ($this->isSuperAdmin()) {
            return !$targetUser->isSuperAdmin();
        }

        if ($this->isAdmin()) {
            return !$targetUser->isSuperAdmin();
        }
        
        return false;
    }

    public function resetToDefaultPassword(): void
    {
        $this->update([
            'password' => \Illuminate\Support\Facades\Hash::make('gafcsc@123'),
            'password_changed_at' => null,
        ]);
    }

    // ==================== DISPLAY HELPERS ====================
    
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrator',
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_HOD => 'Head of Department',
            self::ROLE_MILITARY_ADMIN => 'Military Administrator',
            self::ROLE_CHIEF_CLERK => 'Chief Clerk',
            self::ROLE_CAPO => 'CAPO (Civilian Affairs)',
            self::ROLE_PEO => 'PEO (Principal Executive Officer)',
            self::ROLE_VIEWER => 'Viewer',
            default => 'Unknown'
        };
    }

    // FIX: Add HOD role to getAllRoles
    public static function getAllRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Administrator',
            self::ROLE_ADMIN => 'Administrator (Full Access)',
            self::ROLE_HOD => 'Head of Department',
            self::ROLE_MILITARY_ADMIN => 'Military Administrator (CO, CMDT, Admin Officer)',
            self::ROLE_CHIEF_CLERK => 'Chief Clerk',
            self::ROLE_CAPO => 'CAPO (Civilian Affairs Personnel Officer)',
            self::ROLE_PEO => 'PEO (Principal Executive Officer)',
            self::ROLE_VIEWER => 'Viewer (Regular Staff)',
        ];
    }

    // ==================== SCOPES ====================
    
    public function scopeSuperAdmins($query)
    {
        return $query->where('role', self::ROLE_SUPER_ADMIN);
    }

    public function scopeAdminLevel($query)
    {
        return $query->whereIn('role', [self::ROLE_SUPER_ADMIN, self::ROLE_MILITARY_ADMIN, self::ROLE_ADMIN]);
    }

    public function scopeStaffManagers($query)
    {
        return $query->whereIn('role', [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_CHIEF_CLERK,
            self::ROLE_CAPO,
            self::ROLE_PEO,
        ]);
    }
}