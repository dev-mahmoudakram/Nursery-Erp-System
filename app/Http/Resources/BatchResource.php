<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_number' => $this->batch_number,
            'item' => [
                'id' => $this->item->id,
                'code' => $this->item->item_code,
                'name_ar' => $this->item->name_ar,
            ],
            'nursery_id' => $this->nursery_id,
            'location_id' => $this->location_id,
            'quantity' => (float) $this->quantity,
            'reserved_quantity' => (float) $this->reserved_quantity,
            'damaged_quantity' => (float) $this->damaged_quantity,
            'isolated_quantity' => (float) $this->isolated_quantity,
            'available_quantity' => (float) $this->available_quantity, // computed accessor
            'lifecycle_status' => $this->lifecycle_status,
            'qr_code' => $this->qr_code,
            'last_inventory_date' => $this->last_inventory_date,
        ];
    }
}
