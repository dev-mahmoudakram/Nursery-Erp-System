<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code', 'name_ar', 'name_en', 'scientific_name',
        'main_category_id', 'sub_category_id', 'plant_type', 'pot_size',
        'height_cm', 'crown_diameter_cm', 'approx_age', 'quality_grade',
        'unit_of_measure', 'production_season', 'expected_ready_date',
        'safety_stock', 'production_cost',
        'retail_price', 'wholesale_price', 'contractor_price',
        'project_price', 'government_price', 'clearance_price',
        'min_order_qty', 'image_path', 'status',
    ];

    protected $casts = [
        'expected_ready_date' => 'date',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'main_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'sub_category_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function individualUnits()
    {
        return $this->hasMany(IndividualUnit::class);
    }

    public function storefrontProduct()
    {
        return $this->hasOne(StorefrontProduct::class);
    }

    /**
     * السعر حسب نوع العميل (retail/wholesale/contractor/project/government/clearance)
     */
    public function priceFor(string $customerType): ?float
    {
        $map = [
            'retail' => 'retail_price',
            'wholesale' => 'wholesale_price',
            'contractor' => 'contractor_price',
            'project' => 'project_price',
            'government' => 'government_price',
            'clearance' => 'clearance_price',
        ];

        return $this->{$map[$customerType] ?? 'retail_price'};
    }
}
