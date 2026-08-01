<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursery_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('from_nursery_id')->constrained('nurseries');
            $table->foreignId('to_nursery_id')->constrained('nurseries');

            $table->enum('status', [
                'requested', 'approved', 'preparing', 'in_transit',
                'received', 'inspected', 'closed', 'rejected',
            ])->default('requested');

            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nursery_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursery_transfer_id')->constrained('nursery_transfers')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->decimal('quantity_sent', 12, 2);
            $table->decimal('quantity_received', 12, 2)->nullable(); // يثبت الاستلام قبل الخصم النهائي
            $table->decimal('quantity_damaged_in_transit', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursery_transfer_items');
        Schema::dropIfExists('nursery_transfers');
    }
};
