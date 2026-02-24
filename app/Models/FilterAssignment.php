<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filter_id',
        'category_id',
        'inherit_to_children',
    ];

    protected $casts = [
        'inherit_to_children' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
