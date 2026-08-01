<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('individual_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->unique();      // رقم تعريف مستقل
            $table->string('qr_code')->unique();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('nursery_id')->constrained('nurseries')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('crown_diameter_cm', 8, 2)->nullable();
            $table->string('age')->nullable();
            $table->decimal('price', 12, 2)->nullable();

            $table->enum('lifecycle_status', [
                'new_production', 'growing', 'under_inspection', 'ready_for_sale',
                'reserved', 'preparing', 'loaded', 'delivered', 'returned',
                'needs_rehab', 'isolated', 'damaged', 'disposed',
            ])->default('new_production');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('individual_units');
    }
};
