<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_ar',
        'title_he',
        'slug',
        'description_en',
        'description_ar',
        'description_he',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get title based on current locale.
     */
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_$locale"} ?? $this->title_en;
    }

    /**
     * Get description based on current locale.
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_$locale"} ?? $this->description_en;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($filter) {
            if (empty($filter->slug)) {
                $filter->slug = Str::slug($filter->title_en);
            }
            // Ensure slug uniqueness
            $originalSlug = $filter->slug;
            $counter = 1;
            while (static::where('slug', $filter->slug)->exists()) {
                $filter->slug = $originalSlug . '-' . $counter++;
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────

    public function options()
    {
        return $this->hasMany(FilterOption::class);
    }

    public function activeOptions()
    {
        return $this->hasMany(FilterOption::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function assignments()
    {
        return $this->hasMany(FilterAssignment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'filter_assignments')
            ->withPivot('inherit_to_children')
            ->withTimestamps();
    }

    public function numericValues()
    {
        return $this->hasMany(ProductFilterNumericValue::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title_en');
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Check if this filter is an option-based type (checkbox, radio, boolean).
     */
    public function isOptionBased(): bool
    {
        return in_array($this->type, ['checkbox', 'radio', 'boolean']);
    }

    /**
     * Check if this filter is a numeric/range type.
     */
    public function isNumeric(): bool
    {
        return in_array($this->type, ['range', 'min_max']);
    }

    /**
     * Get the type badge HTML for admin display.
     */
    public function getTypeBadgeAttribute(): string
    {
        $badges = [
            'checkbox' => '<span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">CHECKBOX</span>',
            'radio'    => '<span style="background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">RADIO</span>',
            'range'    => '<span style="background:#d1fae5;color:#065f46;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">RANGE</span>',
            'min_max'  => '<span style="background:#ede9fe;color:#5b21b6;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">MIN/MAX</span>',
            'boolean'  => '<span style="background:#fce7f3;color:#9d174d;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">BOOLEAN</span>',
        ];
        return $badges[$this->type] ?? $this->type;
    }
}
