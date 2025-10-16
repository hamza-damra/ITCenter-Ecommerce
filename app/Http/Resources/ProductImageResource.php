<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'image_path' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'alt_text' => $this->alt_text,
            'is_primary' => $this->is_primary,
            'order' => $this->order,
        ];
    }
}
