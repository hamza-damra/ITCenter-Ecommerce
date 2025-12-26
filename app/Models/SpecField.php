<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SpecField extends Model
{
    use HasFactory;

    protected $fillable = [
        'spec_template_id',
        'key',
        'label_en',
        'label_ar',
        'label_he',
        'type',
        'options',
        'unit',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($field) {
            if (empty($field->key)) {
                $field->key = Str::slug($field->label_en, '_');
            }
        });

        // When a field is deleted, also delete all associated product values
        static::deleting(function ($field) {
            $field->values()->delete();
        });
    }

    /**
     * Get the localized label attribute.
     */
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"label_$locale"} ?? $this->label_en;
    }

    /**
     * Get the template this field belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SpecTemplate::class, 'spec_template_id');
    }

    /**
     * Get all values for this field across products.
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductSpecValue::class);
    }

    /**
     * Scope to only include active fields.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the input type for HTML forms.
     */
    public function getInputTypeAttribute(): string
    {
        return match($this->type) {
            'number' => 'number',
            'boolean' => 'checkbox',
            'select' => 'select',
            default => 'text',
        };
    }

    /**
     * Validate a value against this field's type.
     */
    public function validateValue($value): bool
    {
        if ($this->is_required && empty($value) && $value !== '0' && $value !== false) {
            return false;
        }

        if (empty($value)) {
            return true; // Empty is valid for optional fields
        }

        return match($this->type) {
            'number' => is_numeric($value),
            'boolean' => in_array($value, [true, false, '1', '0', 1, 0], true),
            'select' => in_array($value, $this->options ?? []),
            default => is_string($value),
        };
    }

    /**
     * Format a value for display.
     */
    public function formatValue($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $formatted = match($this->type) {
            'boolean' => ($value === '1' || $value === true || $value === 1) ? __('messages.yes') : __('messages.no'),
            'number' => number_format((float) $value, is_float($value + 0) ? 2 : 0),
            default => (string) $value,
        };

        if ($this->unit && $formatted !== '') {
            $formatted .= ' ' . $this->unit;
        }

        return $formatted;
    }
}






