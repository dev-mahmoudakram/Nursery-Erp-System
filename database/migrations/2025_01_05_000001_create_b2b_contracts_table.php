<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('b2b_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('credit_terms_days')->default(30); // آجل السداد التعاقدي
            $table->decimal('contract_credit_limit', 12, 2)->nullable(); // يجاوز حد ائتمان العميل العام إن وُجد
            $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('b2b_contract_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_contract_id')->constrained('b2b_contracts')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('contract_price', 12, 2); // يتفوق على قوائم الأسعار الست القياسية لهذا العميل
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_contract_items');
        Schema::dropIfExists('b2b_contracts');
    }
};
