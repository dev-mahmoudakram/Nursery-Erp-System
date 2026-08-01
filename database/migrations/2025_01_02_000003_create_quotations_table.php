<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('sales_opportunity_id')->nullable()->constrained('sales_opportunities')->nullOnDelete();
            $table->string('customer_type_snapshot'); // نوع العميل وقت إنشاء العرض (لتثبيت أساس التسعير)

            $table->enum('status', ['draft', 'sent', 'accepted', 'expired', 'converted', 'cancelled'])->default('draft');
            $table->date('valid_until');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('margin_percent', 5, 2)->nullable(); // هامش الربح المحتسب (BR-PRC-02)

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // عند تجاوز الحد الأدنى للسعر
            $table->timestamps();
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('batches');
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('unit_cost', 12, 2)->nullable(); // من production_cost وقت العرض، لاحتساب الهامش
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
