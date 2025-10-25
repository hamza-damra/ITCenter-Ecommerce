<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
        'attempts' => 'integer',
    ];

    /**
     * Generate a random 4-digit code
     */
    public static function generateCode(): string
    {
        return str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code is valid (not used, not expired)
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Scope to get valid codes only
     */
    public function scopeValid($query)
    {
        return $query->where('used', false)
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope to get codes for a specific email
     */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Increment the attempt counter
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }
}
