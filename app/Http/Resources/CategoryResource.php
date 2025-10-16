<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
            'products_count' => $this->when(isset($this->products_count), $this->products_count),
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
        ];
    }
}
