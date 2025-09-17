<?php
// app/Models/Staff.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'service_number',
        'rank',
        'appointment',
        'staff_type',
        'department',
        'location',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Scope a query to search staff members.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('service_number', 'like', "%{$search}%")
                    ->orWhere('rank', 'like', "%{$search}%")
                    ->orWhere('appointment', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to filter by rank.
     */
    public function scopeByRank($query, $rank)
    {
        return $query->where('rank', $rank);
    }

    /**
     * Scope a query to filter by appointment.
     */
    public function scopeByAppointment($query, $appointment)
    {
        return $query->where('appointment', $appointment);
    }

    /**
     * Scope a query to get military staff (typically those with military ranks).
     */
    public function scopeMilitary($query)
    {
        $militaryRanks = ['Colonel', 'Lt Colonel', 'Major', 'Captain', 'Lieutenant', 'Second Lieutenant', 
                         'Sergeant Major', 'Sergeant', 'Corporal', 'Private'];
        return $query->whereIn('rank', $militaryRanks);
    }

    /**
     * Scope a query to get civilian staff.
     */
    public function scopeCivilian($query)
    {
        $civilianTitles = ['Professor', 'Associate Professor', 'Assistant Professor', 'Lecturer', 
                          'Senior Lecturer', 'Dr', 'Mr', 'Mrs', 'Ms', 'Miss'];
        return $query->whereIn('rank', $civilianTitles);
    }

    /**
     * Get initials for avatar display.
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get formatted rank for display.
     */
    public function getFormattedRankAttribute()
    {
        return $this->rank ?: 'Not Specified';
    }

    /**
     * Get formatted appointment for display.
     */
    public function getFormattedAppointmentAttribute()
    {
        return $this->appointment ?: 'Not Specified';
    }

    /**
     * Check if staff member has a rank.
     */
    public function hasRank()
    {
        return !empty($this->rank);
    }

    /**
     * Check if staff member has an appointment.
     */
    public function hasAppointment()
    {
        return !empty($this->appointment);
    }

    /**
     * Check if staff member is military.
     */
    public function isMilitary()
    {
        return $this->staff_type === 'military';
    }

    /**
     * Check if staff member is civilian.
     */
    public function isCivilian()
    {
        return $this->staff_type === 'civilian';
    }

    /**
     * Get all unique ranks in the system.
     */
    public static function getAllRanks()
    {
        return static::whereNotNull('rank')
                    ->where('rank', '!=', '')
                    ->distinct()
                    ->pluck('rank')
                    ->sort()
                    ->values();
    }

    /**
     * Get all unique appointments in the system.
     */
    public static function getAllAppointments()
    {
        return static::whereNotNull('appointment')
                    ->where('appointment', '!=', '')
                    ->distinct()
                    ->pluck('appointment')
                    ->sort()
                    ->values();
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Get all unique departments in the system.
     */
    public static function getAllDepartments()
    {
        return static::whereNotNull('department')
                    ->where('department', '!=', '')
                    ->distinct()
                    ->pluck('department')
                    ->sort()
                    ->values();
    }

    /**
     * Get all unique locations in the system.
     */
    public static function getAllLocations()
    {
        return static::whereNotNull('location')
                    ->where('location', '!=', '')
                    ->distinct()
                    ->pluck('location')
                    ->sort()
                    ->values();
    }

    /**
     * Get staff count by location.
     */
    public static function getLocationStats()
    {
        return static::selectRaw('location, COUNT(*) as count')
                    ->groupBy('location')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get staff count by department.
     */
    public static function getDepartmentStats()
    {
        return static::selectRaw('department, COUNT(*) as count')
                    ->groupBy('department')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get staff count by appointment.
     */
    public static function getAppointmentStats()
    {
        return static::selectRaw('COALESCE(NULLIF(appointment, ""), "Not Specified") as appointment, COUNT(*) as count')
                    ->groupBy('appointment')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get military vs civilian breakdown.
     */
    public static function getMilitaryCivilianStats()
    {
        $military = static::military()->count();
        $civilian = static::civilian()->count();
        $unclassified = static::count() - $military - $civilian;
        
        return [
            'military' => $military,
            'civilian' => $civilian,
            'unclassified' => $unclassified,
            'total' => static::count()
        ];
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
}