<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();       // رقم الصنف
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('scientific_name')->nullable();
            $table->foreignId('main_category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->string('plant_type')->nullable();     // نوع النبات
            $table->string('pot_size')->nullable();       // حجم الحوض أو الكيس
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('crown_diameter_cm', 8, 2)->nullable();
            $table->string('approx_age')->nullable();
            $table->enum('quality_grade', ['A', 'B', 'C'])->default('A');
            $table->string('unit_of_measure')->default('شتلة');
            $table->string('production_season')->nullable();
            $table->date('expected_ready_date')->nullable();

            $table->decimal('safety_stock', 12, 2)->default(0);
            $table->decimal('production_cost', 12, 2)->nullable();

            // قوائم الأسعار
            $table->decimal('retail_price', 12, 2)->nullable();      // سعر التجزئة
            $table->decimal('wholesale_price', 12, 2)->nullable();   // سعر الجملة
            $table->decimal('contractor_price', 12, 2)->nullable();  // سعر المقاولين
            $table->decimal('project_price', 12, 2)->nullable();     // سعر المشروعات
            $table->decimal('government_price', 12, 2)->nullable();  // سعر الجهات الحكومية
            $table->decimal('clearance_price', 12, 2)->nullable();   // سعر التصفية

            $table->decimal('min_order_qty', 12, 2)->default(1);
            $table->string('image_path')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
