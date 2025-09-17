<?php
// app/Models/InventoryCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'color',
        'requires_approval',
        'is_active',
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the inventory items for the category.
     */
    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }

    /**
     * Get active items count.
     */
    public function getActiveItemsCountAttribute()
    {
        return $this->items()->where('status', 'active')->count();
    }

    /**
     * Get total value of items in this category.
     */
    public function getTotalValueAttribute()
    {
        return $this->items()
            ->where('status', 'active')
            ->sum(\DB::raw('unit_cost * total_quantity'));
    }

    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
