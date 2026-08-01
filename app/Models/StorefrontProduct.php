<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontProduct extends Model
{
    protected $fillable = [
        'item_id', 'is_published', 'display_name_ar', 'display_name_en',
        'description_ar', 'description_en', 'cover_image_path', 'published_by',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'item_id', 'item_id');
    }

    /**
     * المخزون الحي المعروض للجمهور = مجموع المتاح للبيع عبر كل الدفعات الجاهزة لهذا الصنف
     * في كل المشاتل (BR-B2C-01: يُعرض فقط ما هو متاح فعليًا، وليس رقمًا ثابتًا).
     */
    public function getLiveStockAttribute(): float
    {
        return $this->item->batches()
            ->where('lifecycle_status', 'ready_for_sale')
            ->get()
            ->sum(fn ($batch) => $batch->available_quantity);
    }

    public function stockAtNursery(int $nurseryId): float
    {
        return $this->item->batches()
            ->where('nursery_id', $nurseryId)
            ->where('lifecycle_status', 'ready_for_sale')
            ->get()
            ->sum(fn ($batch) => $batch->available_quantity);
    }
}
