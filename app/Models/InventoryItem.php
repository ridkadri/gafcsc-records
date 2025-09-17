<?php
// app/Models/InventoryItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'item_code',
        'barcode',
        'category_id',
        'description',
        'manufacturer',
        'model',
        'serial_number',
        'unit_cost',
        'total_quantity',
        'available_quantity',
        'assigned_quantity',
        'maintenance_quantity',
        'minimum_stock_level',
        'condition',
        'location',
        'purchase_date',
        'warranty_expiry',
        'last_inspection',
        'next_inspection',
        'specifications',
        'notes',
        'status',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_inspection' => 'date',
        'next_inspection' => 'date',
        'specifications' => 'array',
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    /**
     * Get the current assignments for the item.
     */
    public function activeAssignments()
    {
        return $this->hasMany(InventoryAssignment::class, 'item_id')
                    ->where('status', 'active');
    }

    /**
     * Get all assignments for the item.
     */
    public function assignments()
    {
        return $this->hasMany(InventoryAssignment::class, 'item_id');
    }

    /**
     * Get all transactions for the item.
     */
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'item_id')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get maintenance records for the item.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(InventoryMaintenance::class, 'item_id');
    }

    /**
     * Check if item is low stock.
     */
    public function isLowStock()
    {
        return $this->available_quantity <= $this->minimum_stock_level;
    }

    /**
     * Check if item needs inspection.
     */
    public function needsInspection()
    {
        return $this->next_inspection && $this->next_inspection->isPast();
    }

    /**
     * Check if warranty is expired.
     */
    public function isWarrantyExpired()
    {
        return $this->warranty_expiry && $this->warranty_expiry->isPast();
    }

    /**
     * Get total value of this item.
     */
    public function getTotalValueAttribute()
    {
        return $this->unit_cost * $this->total_quantity;
    }

    /**
     * Get condition badge color for UI.
     */
    public function getConditionColorAttribute()
    {
        return match($this->condition) {
            'good' => 'bg-green-100 text-green-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-orange-100 text-orange-800',
            'damaged' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope for active items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('available_quantity', '<=', 'minimum_stock_level');
    }

    /**
     * Scope for items needing inspection.
     */
    public function scopeNeedsInspection($query)
    {
        return $query->where('next_inspection', '<=', now());
    }

    /**
     * Search scope.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
    }
}

