<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_code' => $this->item_code,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'quality_grade' => $this->quality_grade,
            'prices' => [
                'retail' => (float) $this->retail_price,
                'wholesale' => (float) $this->wholesale_price,
                'contractor' => (float) $this->contractor_price,
                'project' => (float) $this->project_price,
                'government' => (float) $this->government_price,
                'clearance' => (float) $this->clearance_price,
            ],
            'safety_stock' => (float) $this->safety_stock,
        ];
    }
}
