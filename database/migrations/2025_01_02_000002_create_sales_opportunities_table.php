<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('expected_value', 12, 2)->nullable();
            $table->unsignedTinyInteger('probability')->default(20); // %

            // مسار الفرصة (بند BR-CRM-02) — مبسَّط لمراحل رئيسية قابلة للتوسعة
            $table->enum('stage', [
                'target_customer', 'first_contact', 'needs_analysis', 'quotation_sent',
                'negotiation', 'won', 'lost', 'postponed',
            ])->default('target_customer');

            $table->string('lost_reason')->nullable(); // إلزامي عند stage=lost (يُتحقق منه في الكود)
            $table->foreignId('sales_rep_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('expected_close_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_opportunities');
    }
};
