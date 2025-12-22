<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomProductSpec extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'label_en',
        'label_ar',
        'label_he',
        'value',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns this specification.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the localized label attribute.
     * Falls back to English if the current locale's label is not set.
     */
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        $localizedLabel = $this->{"label_$locale"};
        
        // Return localized label if it exists and is not empty
        if (!empty($localizedLabel)) {
            return $localizedLabel;
        }
        
        // Fallback to English
        return $this->label_en ?? '';
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
