<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storefront_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->unique()->constrained('items')->cascadeOnDelete();
            // نشر الصنف في المتجر العام يتطلب اعتمادًا صريحًا (البند BR-B2C-04: ١٠٠-٢٠٠ صنف موثوق فقط)
            $table->boolean('is_published')->default(false);
            $table->string('display_name_ar')->nullable(); // اسم تسويقي قد يختلف عن اسم الصنف الداخلي
            $table->string('display_name_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_products');
    }
};
