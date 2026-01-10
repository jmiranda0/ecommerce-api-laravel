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
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => $this->stock_quantity,
            'image' => $this->images ? asset('storage/' . $this->images[0]) : null,
            'category' => $this->whenLoaded('category', function () {
                return $this->category->name;
            }),
        ];
    }
}
