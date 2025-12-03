<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        // Common fields
        'service_number',
        'name',
        'contact',
        'type', // 'military' or 'civilian'
        'department',
        'profile_picture', // NEW - for Phase 3
        'appointment',
        'head_of_department_id',
        'is_hod',
        
        // Military-specific fields
        'rank',
        'sex',
        'trade',
        'arm_of_service',
        'deployment',
        'date_of_enrollment',
        'date_of_birth',
        'last_promotion_date',
        
        // Civilian-specific fields
        'present_grade',
        'staff_category', // NEW - Senior/Junior
        'date_of_employment',
        'date_of_posting',
        'job_description',
        'location',
    ];

    protected $casts = [
        'date_of_enrollment' => 'date',
        'date_of_birth' => 'date',
        'last_promotion_date' => 'date',
        'date_of_employment' => 'date',
        'date_of_posting' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user account associated with the staff member.
     * This allows staff members to log in to the system.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'staff_id');
    }

    // Add relationships
    public function headOfDepartment()
    {
        return $this->belongsTo(Staff::class, 'head_of_department_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Staff::class, 'head_of_department_id');
    }

    public function hodUser()
    {
        return $this->hasOne(User::class, 'staff_id')->where('role', User::ROLE_HOD);
    }

    // Scopes
    public function scopeHeadsOfDepartment($query)
    {
        return $query->where('is_hod', true);
    }

    public function scopeSubordinatesOf($query, $hodId)
    {
        return $query->where('head_of_department_id', $hodId);
    }

    // Helper methods
    public function getIsHodAttribute(): bool
    {
        return (bool) ($this->attributes['is_hod'] ?? false);
    }

    public function hasSubordinates(): bool
    {
        return $this->subordinates()->exists();
    }

    /**
     * Get all staff in the same department (for HOD view)
     */
    public function getDepartmentStaff()
    {
        if (!$this->department) {
            return collect();
        }
        
        return Staff::where('department', $this->department)->get();
    }

    /**
     * NEW - Relationship to documents (Phase 3)
     */
    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    /**
     * Get all inventory assignments for this staff member.
     */
    public function inventoryAssignments()
    {
        return $this->hasMany(InventoryAssignment::class);
    }

    /**
     * Get active inventory assignments for this staff member.
     */
    public function activeInventoryAssignments()
    {
        return $this->hasMany(InventoryAssignment::class)->where('status', 'active');
    }

    /**
     * Get overdue inventory assignments for this staff member.
     */
    public function overdueInventoryAssignments()
    {
        return $this->hasMany(InventoryAssignment::class)
                    ->where('status', 'active')
                    ->where('expected_return_date', '<', now());
    }

    /**
     * Get inventory transactions for this staff member.
     */
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // ==================== USER ACCOUNT METHODS ====================

    /**
     * Check if staff member has a user account (can log in)
     */
    public function hasUserAccount(): bool
    {
        return $this->user()->exists();
    }

    /**
     * Get the role of the user if they have an account
     */
    public function getUserRole(): ?string
    {
        return $this->user?->role;
    }

    // ==================== PROFILE PICTURE METHODS (NEW - Phase 3) ====================

    /**
     * Get profile picture URL
     */
    public function getProfilePictureUrl(): string
    {
        if ($this->profile_picture && Storage::disk('public')->exists($this->profile_picture)) {
            return asset('storage/' . $this->profile_picture);
        }
        
        // Return default avatar
        return $this->getDefaultAvatar();
    }

    /**
     * Get default avatar
     */
    public function getDefaultAvatar(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=random';
    }

    /**
     * Check if staff has profile picture
     */
    public function hasProfilePicture(): bool
    {
        return $this->profile_picture && Storage::disk('public')->exists($this->profile_picture);
    }

    // ==================== DOCUMENT METHODS (NEW - Phase 3) ====================

    /**
     * Get documents by type
     */
    public function getDocumentsByType(string $type)
    {
        return $this->documents()->where('document_type', $type)->get();
    }

    /**
     * Get expired documents
     */
    public function getExpiredDocuments()
    {
        return $this->documents()
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now())
            ->get();
    }

    // ==================== TYPE CHECKING ====================

    /**
     * Check if staff is military
     */
    public function isMilitary()
    {
        return $this->type === 'military';
    }

    /**
     * Check if staff is civilian
     */
    public function isCivilian()
    {
        return $this->type === 'civilian';
    }

    // ==================== COMPUTED ATTRIBUTES ====================

    /**
     * Get age from date of birth
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Get years of service
     */
    public function getYearsOfServiceAttribute()
    {
        if ($this->isMilitary() && $this->date_of_enrollment) {
            return $this->date_of_enrollment->diffInYears(now());
        }
        
        if ($this->isCivilian() && $this->date_of_employment) {
            return $this->date_of_employment->diffInYears(now());
        }
        
        return null;
    }

    /**
     * Get total value of assigned inventory.
     */
    public function getTotalAssignedInventoryValueAttribute()
    {
        return $this->activeInventoryAssignments()
                    ->join('inventory_items', 'inventory_assignments.item_id', '=', 'inventory_items.id')
                    ->sum(\DB::raw('inventory_assignments.quantity * inventory_items.unit_cost'));
    }

    /**
     * Get count of active inventory assignments.
     */
    public function getActiveInventoryCountAttribute()
    {
        return $this->activeInventoryAssignments()->count();
    }

    /**
     * Get count of overdue inventory assignments.
     */
    public function getOverdueInventoryCountAttribute()
    {
        return $this->overdueInventoryAssignments()->count();
    }

    // ==================== INVENTORY CHECKS ====================

    /**
     * Check if staff member has overdue inventory.
     */
    public function hasOverdueInventory()
    {
        return $this->overdueInventoryAssignments()->exists();
    }

    /**
     * Check if staff member has any assigned inventory.
     */
    public function hasAssignedInventory()
    {
        return $this->activeInventoryAssignments()->exists();
    }

    // ==================== STATIC OPTION GETTERS ====================

    /**
     * Military deployment options
     */
    public static function getDeploymentOptions()
    {
        return [
            'Leave' => 'Leave',
            'T Leave' => 'Terminal Leave',
            'On Ground' => 'On Ground',
            'Indisposed' => 'Indisposed',
            'Mission' => 'Mission',
        ];
    }

    /**
     * Trade options for military staff
     */
    public static function getTrades()
    {
        return [
            'Infantry' => 'Infantry',
            'Artillery' => 'Artillery',
            'Armoured' => 'Armoured',
            'Engineers' => 'Engineers',
            'Signals' => 'Signals',
            'Intelligence' => 'Intelligence',
            'Logistics' => 'Logistics',
            'Medical' => 'Medical',
            'Administration' => 'Administration',
            'Finance' => 'Finance',
            'Legal' => 'Legal',
            'IT' => 'IT',
            'Technical' => 'Technical',
            'Special Forces' => 'Special Forces',
            'Military Police' => 'Military Police',
        ];
    }

    /**
     * Arms of Service options
     */
    public static function getArmsOfService()
    {
        return [
            'Army' => 'Army',
            'Airforce' => 'Airforce',
            'Navy' => 'Navy',
        ];
    }

    /**
     * Military ranks (Army, Navy, and Air Force)
     */
    public static function getMilitaryRanks()
    {
        return [
            // ARMY RANKS - Officers (Abbreviated)
            'GEN' => 'GEN',
            'LT GEN' => 'LT GEN',
            'MAJ GEN' => 'MAJ GEN',
            'BRIG GEN' => 'BRIG GEN',
            'COL' => 'COL',
            'LT COL' => 'LT COL',
            'MAJ' => 'MAJ',
            'CAPT' => 'CAPT',
            'LT' => 'LT',
            '2LT' => '2LT',
            
            // ARMY RANKS - Other Ranks (Abbreviated)
            'WO I' => 'WO I',
            'WO II' => 'WO II',
            'S/SGT' => 'S/SGT',
            'SGT' => 'SGT',
            'CPL' => 'CPL',
            'L/CPL' => 'L/CPL',
            'PTE' => 'PTE',
            
            // NAVY RANKS - Flag Officers (Abbreviated)
            'ADM' => 'ADM',
            'V/ADM' => 'V/ADM',
            'R/ADM' => 'R/ADM',
            'CDRE' => 'CDRE',
            
            // NAVY RANKS - Senior Officers (Abbreviated)
            'CAPT (N)' => 'CAPT (N)',
            'CDR' => 'CDR',
            'LT CDR' => 'LT CDR',
            
            // NAVY RANKS - Junior Officers (Abbreviated)
            'LT (N)' => 'LT (N)',
            'S/LT' => 'S/LT',
            'MIDS' => 'MIDS',
            
            // NAVY RANKS - Ratings (Abbreviated)
            'CPO' => 'CPO',
            'PO' => 'PO',
            'LS' => 'LS',
            'AB' => 'AB',
            'OS' => 'OS',
            
            // AIR FORCE RANKS - Air Officers (Abbreviated)
            'ACM' => 'ACM',
            'AM' => 'AM',
            'AVM' => 'AVM',
            'A/CDRE' => 'A/CDRE',
            
            // AIR FORCE RANKS - Senior Officers (Abbreviated)
            'GP CAPT' => 'GP CAPT',
            'W/CDR' => 'W/CDR',
            'SQN LDR' => 'SQN LDR',
            
            // AIR FORCE RANKS - Junior Officers (Abbreviated)
            'FLT LT' => 'FLT LT',
            'FLG OFF' => 'FLG OFF',
            'PLT OFF' => 'PLT OFF',
            
            // AIR FORCE RANKS - Airmen (Abbreviated)
            'WO (A)' => 'WO (A)',
            'F/SGT' => 'F/SGT',
            'SGT (A)' => 'SGT (A)',
            'CPL (A)' => 'CPL (A)',
            'L/CPL (A)' => 'L/CPL (A)',
            'ACM1' => 'ACM1',
        ];
    }

    /**
     * Alias for getMilitaryRanks() - for backward compatibility
     */
    public static function getRanks()
    {
        return self::getMilitaryRanks();
    }

    // Appointment options for military staff
    public const APPOINTMENTS = [
        'Commandant' => 'Commandant',
        'Deputy Commandant' => 'Deputy Commandant',
        'Assistant Commandant (SNR Div)' => 'Assistant Commandant (SNR Div)',
        'Assistant Commandant (JNR Div)' => 'Assistant Commandant (JNR Div)',
        'CCOORD' => 'CCOORD',
        'DIR CORP Affairs' => 'DIR CORP Affairs',
        'D Int Staff & Students Div' => 'D Int Staff & Students Div',
        'D R&D' => 'D R&D',
        'GSO I COORD (SNR DIV)' => 'GSO I COORD (SNR DIV)',
        'GSO I COORD (JNR DIV)' => 'GSO I COORD (JNR DIV)',
        'CO' => 'CO',
        'COMD FINANCE COMTROLLER' => 'COMD FINANCE COMTROLLER',
        'IT MANAGER' => 'IT MANAGER',
        'DD CORP AFFAIRS' => 'DD CORP AFFAIRS',
        'GSO II (A & Q)' => 'GSO II (A & Q)',
        'GSO II COORD (SNR DIV)' => 'GSO II COORD (SNR DIV)',
        'GSO II COORD (JNR DIV)' => 'GSO II COORD (JNR DIV)',
        'Comd PRO' => 'Comd PRO',
        'Chief Clerk' => 'Chief Clerk',
        'Admin Officer' => 'Admin Officer',
        'ADC TO COMDT' => 'ADC TO COMDT',
        'CI JT STUDIES' => 'CI JT STUDIES',
        'CI ARMY (A)' => 'CI ARMY (A)',
        'CI ARMY (B)' => 'CI ARMY (B)',
        'CI (JNR DIV)' => 'CI (JNR DIV)',
        'CI NAVAL FACULTY' => 'CI NAVAL FACULTY',
        'CI AIR FACULTY' => 'CI AIR FACULTY'

    ];

    public static function getAppointments(): array
    {
        return self::APPOINTMENTS;
    }

    /**
    * Civilian grades
    */
    public static function getCivilianGrades()
    {
        return [
            'SAO' => 'SAO',
            'A/CEO' => 'A/CEO',
            'PEO' => 'PEO',
            'SEO' => 'SEO',
            'ADMIN OFFR GDIII' => 'ADMIN OFFR GDIII',
            'ADMIN OFFR GD IV' => 'ADMIN OFFR GD IV',
            'PRIN PRIV SEC' => 'PRIN PRIV SEC',
            'SNR PRIV SECRETARY' => 'SNR PRIV SECRETARY',
            'PRIN PROC OFFR' => 'PRIN PROC OFFR',
            'LIB (ACADEMICS)' => 'LIB (ACADEMICS)',
            'SNR LIB' => 'SNR LIB',
            'DEP CH LIB' => 'DEP CH LIB',
            'LIBRARIAN' => 'LIBRARIAN',
            'ACADEMIC DEAN' => 'ACADEMIC DEAN',
            'REGISTRAR' => 'REGISTRAR',
            'DEP REGISTRAR' => 'DEP REGISTRAR',
            'ASST REGISTRAR' => 'ASST REGISTRAR',
            'SNR RESEARCH FELLOW' => 'SNR RESEARCH FELLOW',
            'SNR RESEACH FELLOW' => 'SNR RESEACH FELLOW',
            'ASST CH ACCT' => 'ASST CH ACCT',
            'PRIN TECH OFFR' => 'PRIN TECH OFFR',
            'SNR TECH OFFR' => 'SNR TECH OFFR',
            'SUPPLY OFFR' => 'SUPPLY OFFR',
            'IT MNGR' => 'IT MNGR',
            'TECH PROG MGR' => 'TECH PROG MGR',
            'PROGRAMMER' => 'PROGRAMMER',
            'SNR COMP TECH' => 'SNR COMP TECH',
            'SNR ASST INFO TECH' => 'SNR ASST INFO TECH',
            'INFO TECH' => 'INFO TECH',
            'ASST INFO TECH' => 'ASST INFO TECH',
            'ASST PROG' => 'ASST PROG',
            'PRIN COM TECH' => 'PRIN COM TECH',
            'PRIN WKS SUPT' => 'PRIN WKS SUPT',
            'CH WKS SUPT' => 'CH WKS SUPT',
            'SNR WKS SUPT (ELECT)' => 'SNR WKS SUPT (ELECT)',
            'WKS SUPT(ELECT)' => 'WKS SUPT(ELECT)',
            'CHIEF YARD F\'MAN' => 'CHIEF YARD F\'MAN',
            'WKS SUPT' => 'WKS SUPT',
            'SNR WKS SUPT (VEH MECH)' => 'SNR WKS SUPT (VEH MECH)',
            'WKS SUPT(VEH/MECH)' => 'WKS SUPT(VEH/MECH)',
            'WKS SUPT(TAILOR)' => 'WKS SUPT(TAILOR)',
            'PRIN CAT OFFR' => 'PRIN CAT OFFR',
            'SNR CAT OFFR' => 'SNR CAT OFFR',
            'CH TECH OFFR' => 'CH TECH OFFR',
            'SNR SUPPLY OFFR' => 'SNR SUPPLY OFFR',
            'INFO OFFR' => 'INFO OFFR',
        ];
    }

    /**
     * Alias for getCivilianGrades() - for backward compatibility
     */
    public static function getGrades()
    {
        return self::getCivilianGrades();
    }

    /**
    * Job description options for civilians
    */
    public static function getJobDescriptions()
    {
        return [
            'CAPO' => 'CAPO',
            'CLK DUTIES' => 'CLK DUTIES',
            'COMP OPR' => 'COMP OPR',
            'IT TECH' => 'IT TECH',
            'PA TO DEP COMDT' => 'PA TO DEP COMDT',
            'PA TO C COORD' => 'PA TO C COORD',
            'LIB DUTIES' => 'LIB DUTIES',
            'CORPORATE AFFAIRS' => 'CORPORATE AFFAIRS',
            'PA TO DEAN' => 'PA TO DEAN',
            'PA TO ASCOM' => 'PA TO ASCOM',
            'PA TO DECOM' => 'PA TO DECOM',
            'LECTURER' => 'LECTURER',
            'ACADEMIC' => 'ACADEMIC',
            'REGISTRAR' => 'REGISTRAR',
            'RESEARCH OFFR' => 'RESEARCH OFFR',
            'EXAMS' => 'EXAMS',
            'RECORDS' => 'RECORDS',
            'ACCOUNTANT' => 'ACCOUNTANT',
            'GRAPHIC DESIGNER' => 'GRAPHIC DESIGNER',
            'PRINTING' => 'PRINTING',
            'STORES' => 'STORES',
            'BIA' => 'BIA',
            'EQT OPR' => 'EQT OPR',
            'ELECTRICAL ENGINEER' => 'ELECTRICAL ENGINEER',
            'FRIDGE/AIR COND MECH' => 'FRIDGE/AIR COND MECH',
            'VEH MECH' => 'VEH MECH',
            'GEN ELECT' => 'GEN ELECT',
            'DRIVER' => 'DRIVER',
            'PLUMBER' => 'PLUMBER',
            'VEH/MECH' => 'VEH/MECH',
            'TAILORING' => 'TAILORING',
            'MATRON' => 'MATRON',
            'COOK' => 'COOK',
            'SIGNALS' => 'SIGNALS',
            'PR' => 'PR',
        ];
    }

    /**
     * Get all departments
     */
    public static function getAllDepartments()
    {
        return self::whereNotNull('department')
                   ->where('department', '!=', '')
                   ->distinct()
                   ->pluck('department')
                   ->sort()
                   ->values();
    }

    /**
     * Get all locations (for civilians)
     */
    public static function getAllLocations()
    {
        return self::where('type', 'civilian')
                   ->whereNotNull('location')
                   ->where('location', '!=', '')
                   ->distinct()
                   ->pluck('location')
                   ->sort()
                   ->values();
    }

    // ==================== VALIDATION RULES ====================

    /**
     * Validation rules for military staff
     */
    public static function militaryValidationRules($id = null)
    {
        return [
            'service_number' => 'required|string|unique:staff,service_number,' . $id,
            'name' => 'required|string|max:255',
            'rank' => 'required|string',
            'sex' => 'required|in:Male,Female',
            'trade' => 'nullable|string|max:255',
            'arm_of_service' => 'nullable|string|max:255',
            'deployment' => 'nullable|in:Leave,T Leave,On Ground,Indisposed,Mission',
            'date_of_enrollment' => 'nullable|date|before_or_equal:today',
            'date_of_birth' => 'nullable|date|before:today',
            'last_promotion_date' => 'nullable|date|before_or_equal:today',
            'department' => 'nullable|string|max:255',
            'appointment' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:military',
            'contact' => 'nullable|string|max:20',
        ];
    }

    /**
     * Validation rules for civilian staff
     */
    public static function civilianValidationRules($id = null)
    {
        return [
            'service_number' => 'required|string|unique:staff,service_number,' . $id,
            'name' => 'required|string|max:255',
            'present_grade' => 'required|string|max:255',
            'staff_category' => 'nullable|in:Senior,Junior',
            'appointment' => 'nullable|string|max:255',
            'deployment' => 'nullable|in:Leave,T Leave,On Ground,Indisposed',
            'date_of_employment' => 'nullable|date|before_or_equal:today',
            'date_of_birth' => 'nullable|date|before:today',
            'last_promotion_date' => 'nullable|date|before_or_equal:today',
            'date_of_posting' => 'nullable|date',
            'job_description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:civilian',
            'contact' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get military/civilian stats
     */
    public static function getMilitaryCivilianStats()
    {
        return [
            'military' => self::where('type', 'military')->count(),
            'civilian' => self::where('type', 'civilian')->count(),
        ];
    }

    // ==================== SCOPES ====================

    /**
     * Scope for military staff
     */
    public function scopeMilitary($query)
    {
        return $query->where('type', 'military');
    }

    /**
     * Scope for civilian staff
     */
    public function scopeCivilian($query)
    {
        return $query->where('type', 'civilian');
    }

    // ==================== MODEL EVENTS ====================

    /**
     * Boot method - handles model events and HOD auto-sync
     */
    protected static function boot()
    {
        parent::boot();
        
	// Auto-generate staff_id when creating new staff
	static::creating(function ($staff) {
    	   if (empty($staff->staff_id)) {
        	// Generate unique staff_id
        	$lastStaff = Staff::orderBy('id', 'desc')->first();
        	$nextNumber = $lastStaff ? $lastStaff->id + 1 : 1;
        	$staff->staff_id = 'STF' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    	}
	});
        // Auto-sync HOD relationships when department or is_hod changes
        static::saved(function ($staff) {
            // If staff becomes HOD, assign all department staff to them
            if ($staff->isDirty('is_hod') && $staff->is_hod && $staff->department) {
                Staff::where('department', $staff->department)
                     ->where('id', '!=', $staff->id)
                     ->where('is_hod', false)
                     ->update(['head_of_department_id' => $staff->id]);
            }
            
            // If staff is no longer HOD, remove subordinates
            if ($staff->isDirty('is_hod') && !$staff->is_hod) {
                Staff::where('head_of_department_id', $staff->id)
                     ->update(['head_of_department_id' => null]);
            }
            
            // If department changed and staff is not an HOD, reassign to new department HOD
            if ($staff->isDirty('department') && !$staff->is_hod) {
                $hod = Staff::where('is_hod', true)
                           ->where('department', $staff->department)
                           ->where('id', '!=', $staff->id)
                           ->first();
                
                if ($hod) {
                    $staff->head_of_department_id = $hod->id;
                    $staff->saveQuietly();
                } else {
                    $staff->head_of_department_id = null;
                    $staff->saveQuietly();
                }
            }
        });

        // When staff is deleted, if they are HOD, clear subordinates and delete files
        static::deleting(function ($staff) {
            // Clear subordinates if HOD
            if ($staff->is_hod) {
                Staff::where('head_of_department_id', $staff->id)
                     ->update(['head_of_department_id' => null]);
            }
            
            // Delete profile picture
            if ($staff->profile_picture && Storage::exists($staff->profile_picture)) {
                Storage::delete($staff->profile_picture);
            }
            
            // Documents will be deleted automatically via cascade in migration
        });
    }
}
