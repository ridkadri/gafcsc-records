<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StaffDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'uploaded_by',
        'document_type',
        'document_name',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
        'issue_date',
        'expiry_date',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Document type constants
    const TYPE_CERTIFICATE = 'certificate';
    const TYPE_ID_CARD = 'id_card';
    const TYPE_MEDICAL = 'medical';
    const TYPE_QUALIFICATION = 'qualification';

    /**
     * Get all document types
     */
    public static function getDocumentTypes(): array
    {
        return [
            self::TYPE_CERTIFICATE => 'Certificate',
            self::TYPE_ID_CARD => 'ID Card',
            self::TYPE_MEDICAL => 'Medical Record',
            self::TYPE_QUALIFICATION => 'Qualification',
        ];
    }

    /**
     * Get document type display name
     */
    public function getDocumentTypeDisplayName(): string
    {
        return self::getDocumentTypes()[$this->document_type] ?? 'Unknown';
    }

    /**
     * Relationship to staff
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Relationship to user who uploaded
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get full file URL
     */
    public function getFileUrl(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Check if document is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }

    /**
     * Check if document expires soon (within 30 days)
     */
    public function expiresSoon(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isFuture() 
            && $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * Get icon for document type
     */
    public function getIcon(): string
    {
        return match($this->document_type) {
            self::TYPE_CERTIFICATE => '📜',
            self::TYPE_ID_CARD => '🪪',
            self::TYPE_MEDICAL => '🏥',
            self::TYPE_QUALIFICATION => '🎓',
            default => '📄'
        };
    }

    /**
     * Get color for document type
     */
    public function getColor(): string
    {
        return match($this->document_type) {
            self::TYPE_CERTIFICATE => 'blue',
            self::TYPE_ID_CARD => 'green',
            self::TYPE_MEDICAL => 'red',
            self::TYPE_QUALIFICATION => 'purple',
            default => 'gray'
        };
    }

    /**
     * Delete document file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        });
    }
}