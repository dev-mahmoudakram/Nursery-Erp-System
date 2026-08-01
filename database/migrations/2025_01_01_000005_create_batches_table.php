<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('nursery_id')->constrained('nurseries')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->date('production_date')->nullable();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('reserved_quantity', 12, 2)->default(0);
            $table->decimal('damaged_quantity', 12, 2)->default(0);
            $table->decimal('isolated_quantity', 12, 2)->default(0);

            $table->string('size')->nullable();
            $table->enum('quality_grade', ['A', 'B', 'C'])->default('A');

            // حالة دورة حياة الدفعة
            $table->enum('lifecycle_status', [
                'new_production', 'growing', 'under_inspection', 'ready_for_sale',
                'reserved', 'preparing', 'loaded', 'delivered', 'returned',
                'needs_rehab', 'isolated', 'damaged', 'disposed',
            ])->default('new_production');

            $table->string('qr_code')->nullable()->unique();
            $table->string('image_path')->nullable();

            $table->date('last_inventory_date')->nullable();
            $table->date('expected_ready_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
