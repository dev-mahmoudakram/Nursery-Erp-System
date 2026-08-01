<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_number')->unique();
            $table->foreignId('sales_order_id')->constrained('sales_orders');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();

            $table->enum('status', [
                'pending', 'loaded', 'in_transit', 'delivered', 'partially_delivered', 'failed', 'returned',
            ])->default('pending');

            $table->date('scheduled_date')->nullable();
            $table->decimal('delivery_cost', 10, 2)->nullable();
            $table->string('route_notes')->nullable();

            // إثبات التسليم (BR-LOG-02)
            $table->string('signed_by_name')->nullable();
            $table->string('proof_photo_path')->nullable();
            $table->string('proof_signature_path')->nullable();
            $table->string('failure_reason')->nullable(); // عند status=failed/returned

            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_order_id')->constrained('delivery_orders')->cascadeOnDelete();
            $table->foreignId('sales_order_item_id')->constrained('sales_order_items');
            $table->decimal('quantity', 12, 2); // الكمية المخصصة لهذا التسليم تحديدًا
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_order_items');
        Schema::dropIfExists('delivery_orders');
    }
};
