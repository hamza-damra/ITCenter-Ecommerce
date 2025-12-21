<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name_en',
        'name_ar',
        'name_he',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the localized name attribute.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_en;
    }

    /**
     * Get the category this template belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the fields for this template.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(SpecField::class)->orderBy('sort_order');
    }

    /**
     * Get only active fields for this template.
     */
    public function activeFields(): HasMany
    {
        return $this->hasMany(SpecField::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get required fields for this template.
     */
    public function requiredFields(): HasMany
    {
        return $this->hasMany(SpecField::class)
            ->where('is_required', true)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this template has any fields.
     */
    public function hasFields(): bool
    {
        return $this->fields()->exists();
    }

    /**
     * Get the count of products using this template's fields.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->category->products()->count();
    }
}


