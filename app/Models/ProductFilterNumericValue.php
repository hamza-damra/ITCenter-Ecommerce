<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFilterNumericValue extends Model
{
    public $timestamps = false;

    protected $table = 'product_filter_numeric_values';

    protected $fillable = [
        'product_id',
        'filter_id',
        'numeric_value',
    ];

    protected $casts = [
        'numeric_value' => 'decimal:4',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }
}
