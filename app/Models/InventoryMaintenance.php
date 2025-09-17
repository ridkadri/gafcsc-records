<?php
// app/Models/InventoryMaintenance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    use HasFactory;

    protected $table = 'inventory_maintenance';

    protected $fillable = [
        'item_id',
        'maintenance_type',
        'scheduled_date',
        'completed_date',
        'status',
        'description',
        'notes',
        'cost',
        'performed_by',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the item for the maintenance.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the user who created the maintenance record.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if maintenance is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'scheduled' && $this->scheduled_date->isPast();
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => $this->isOverdue() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope for overdue maintenance.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_date', '<', now());
    }
}