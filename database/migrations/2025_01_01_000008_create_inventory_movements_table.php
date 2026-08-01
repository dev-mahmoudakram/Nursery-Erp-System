<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->cascadeOnDelete();
            $table->foreignId('individual_unit_id')->nullable()->constrained('individual_units')->cascadeOnDelete();

            $table->enum('movement_type', [
                'opening_balance', 'receive', 'transfer_between_nurseries', 'transfer_between_locations',
                'reserve', 'unreserve', 'prepare', 'deliver', 'return', 'damage', 'dispose',
                'periodic_count', 'surprise_count', 'full_count', 'count_adjustment',
            ]);

            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();

            $table->decimal('quantity', 12, 2);
            $table->decimal('counted_quantity', 12, 2)->nullable();   // للجرد فقط
            $table->decimal('quantity_diff', 12, 2)->nullable();      // فرق الجرد
            $table->string('diff_reason')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamp('movement_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
