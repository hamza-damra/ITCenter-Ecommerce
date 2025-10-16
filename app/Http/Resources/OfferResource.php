<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'discount' => [
                'type' => $this->discount_type,
                'value' => (float) $this->discount_value,
            ],
            'min_purchase_amount' => $this->min_purchase_amount ? (float) $this->min_purchase_amount : null,
            'usage' => [
                'max_uses' => $this->max_uses,
                'uses_count' => $this->uses_count,
                'remaining' => $this->max_uses ? ($this->max_uses - $this->uses_count) : null,
            ],
            'period' => [
                'start_date' => $this->start_date?->toISOString(),
                'end_date' => $this->end_date?->toISOString(),
                'is_active' => $this->is_active,
                'is_valid' => $this->isValid(),
            ],
            'banner_image' => $this->banner_image ? asset('storage/' . $this->banner_image) : null,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
