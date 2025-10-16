<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'short_description_en' => $this->short_description_en,
            'short_description_ar' => $this->short_description_ar,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'price' => [
                'regular' => (float) $this->price,
                'sale' => $this->sale_price ? (float) $this->sale_price : null,
                'cost' => (float) $this->cost_price,
                'discount_percentage' => $this->discount_percentage,
                'final' => (float) $this->final_price,
            ],
            'stock' => [
                'quantity' => $this->stock_quantity,
                'min_quantity' => $this->min_stock_quantity,
                'status' => $this->stock_status,
                'is_in_stock' => $this->is_in_stock,
                'track_stock' => $this->track_stock,
            ],
            'main_image' => $this->main_image ? asset('storage/' . $this->main_image) : null,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_new' => $this->is_new,
            'is_bestseller' => $this->is_bestseller,
            'dimensions' => [
                'weight' => $this->weight,
                'length' => $this->length,
                'width' => $this->width,
                'height' => $this->height,
            ],
            'warranty' => $this->warranty,
            'specifications' => $this->specifications,
            'stats' => [
                'views_count' => $this->views_count,
                'sales_count' => $this->sales_count,
                'avg_rating' => (float) $this->avg_rating,
                'reviews_count' => $this->reviews_count,
            ],
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
