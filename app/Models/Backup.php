<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'type',
        'size',
        'expires_at',
        'created_by',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Check if backup is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false; // Never expires
        }

        return $this->expires_at->isPast();
    }

    /**
     * Get human-readable expiration status
     *
     * @return string
     */
    public function getExpirationStatusAttribute(): string
    {
        if ($this->expires_at === null) {
            return __('messages.Never expires');
        }

        if ($this->isExpired()) {
            return __('messages.Expired');
        }

        return __('messages.Expires in') . ' ' . $this->expires_at->diffForHumans();
    }

    /**
     * Scope to get expired backups
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<', now());
    }

    /**
     * Scope to get active (not expired) backups
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }

    /**
     * Get formatted size
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->size;
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
