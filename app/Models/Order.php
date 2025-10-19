<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => __t('messages.order_status_pending'),
            'processing' => __t('messages.order_status_processing'),
            'shipped' => __t('messages.order_status_shipped'),
            'delivered' => __t('messages.order_status_delivered'),
            'cancelled' => __t('messages.order_status_cancelled'),
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => '#FFA500',
            'processing' => '#2762f3',
            'shipped' => '#9333ea',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            default => '#6b7280',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => __t('messages.payment_pending'),
            'paid' => __t('messages.payment_paid'),
            'failed' => __t('messages.payment_failed'),
            'refunded' => __t('messages.payment_refunded'),
            default => $this->payment_status,
        };
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
