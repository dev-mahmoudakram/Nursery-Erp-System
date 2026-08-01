<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            // بيانات ضيف (لا يتطلب حساب مسبق) - BR-B2C-02
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city')->nullable();

            $table->foreignId('nursery_id')->constrained('nurseries'); // مصدر التجهيز
            $table->enum('delivery_method', ['delivery', 'pickup'])->default('delivery');
            $table->string('delivery_address')->nullable();
            $table->date('requested_delivery_date')->nullable();

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->enum('payment_method', ['online', 'cash_on_delivery'])->default('online');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

            // حالة الطلب من استلامه حتى ربطه بالنظام الداخلي (BR-B2C-01/02)
            $table->enum('status', [
                'pending_review', 'confirmed', 'preparing', 'out_for_delivery',
                'delivered', 'cancelled', 'returned',
            ])->default('pending_review');

            // بمجرد تأكيد الطلب من الموظف، يُنشأ عرض سعر/أمر بيع حقيقي بنفس محرك CRM
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('online_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_order_id')->constrained('online_orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_order_items');
        Schema::dropIfExists('online_orders');
    }
};
