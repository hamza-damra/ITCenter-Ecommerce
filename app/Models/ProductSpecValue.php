<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'spec_field_id',
        'value',
    ];

    /**
     * Get the product this value belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the spec field this value belongs to.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(SpecField::class, 'spec_field_id');
    }

    /**
     * Get the formatted value for display.
     */
    public function getFormattedValueAttribute(): string
    {
        if (!$this->field) {
            return $this->value ?? '';
        }

        return $this->field->formatValue($this->value);
    }

    /**
     * Get the casted value based on field type.
     */
    public function getCastedValueAttribute()
    {
        if (!$this->field) {
            return $this->value;
        }

        return match($this->field->type) {
            'number' => is_numeric($this->value) ? (float) $this->value : null,
            'boolean' => in_array($this->value, ['1', 'true', true, 1], true),
            default => $this->value,
        };
    }
}






