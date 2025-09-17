<?php
// app/Models/InventoryTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'staff_id',
        'transaction_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'notes',
        'processed_by',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the item for the transaction.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the staff member involved in the transaction.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who processed the transaction.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get transaction type color for UI.
     */
    public function getTransactionTypeColorAttribute()
    {
        return match($this->transaction_type) {
            'check_out' => 'bg-orange-100 text-orange-800',
            'check_in' => 'bg-green-100 text-green-800',
            'purchase' => 'bg-blue-100 text-blue-800',
            'disposal' => 'bg-red-100 text-red-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'transfer' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}