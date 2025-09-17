<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'staff_id',
        'quantity',
        'assigned_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'assignment_notes',
        'return_notes',
        'condition_on_return',
        'assigned_by',
        'returned_to',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    /**
     * Get the item that is assigned.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the staff member who has the assignment.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who made the assignment.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the user who processed the return.
     */
    public function returnedTo()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    /**
     * Check if assignment is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'active' && 
               $this->expected_return_date && 
               $this->expected_return_date->isPast();
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => $this->isOverdue() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800',
            'returned' => 'bg-blue-100 text-blue-800',
            'overdue' => 'bg-red-100 text-red-800',
            'lost' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope for active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for overdue assignments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->where('expected_return_date', '<', now());
    }
}