<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // ID y número de orden legible
            'id' => $this->id,
            'order_number' => 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
            
            // Estados con valor y etiqueta traducida
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->getLabel(),
            ],
            'payment_status' => [
                'value' => $this->payment_status->value,
                'label' => $this->payment_status->getLabel(),
            ],
            'payment_method' => $this->payment_method->value,
            
            // Información del cliente agrupada
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'phone' => $this->customer_phone,
            ],
            
            // Información de envío agrupada
            'shipping' => [
                'address' => $this->address,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
            ],
            
            // Total como número, no string
            'total_amount' => (float) $this->total_amount,
            
            // Items solo si fueron cargados con ->with('items')
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            
            // Fechas en formato legible
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
