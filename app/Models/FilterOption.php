<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'filter_id',
        'label_en',
        'label_ar',
        'label_he',
        'value_slug',
        'color_code',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the label based on current locale.
     */
    public function getLabelAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"label_$locale"} ?? $this->label_en;
    }

    // ── Relationships ─────────────────────────────────────────

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filter_option');
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
