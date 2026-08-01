<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_tenders', function (Blueprint $table) {
            $table->id();
            $table->string('tender_number')->unique();
            $table->string('title');
            $table->string('government_entity_name');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete(); // الجهة كعميل (نوع government)
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete(); // العرض الفني/المالي المُقدَّم

            $table->date('announcement_date')->nullable();
            $table->date('submission_deadline');
            $table->decimal('tender_document_fee', 10, 2)->nullable(); // قيمة كراسة الشروط
            $table->decimal('bid_bond_amount', 12, 2)->nullable();     // قيمة الضمان الابتدائي
            $table->decimal('estimated_value', 12, 2)->nullable();

            // حالة المنافسة (BR-B2G-01)
            $table->enum('status', [
                'evaluating', 'decided_no_bid', 'preparing_offer', 'submitted',
                'won', 'lost', 'cancelled',
            ])->default('evaluating');

            $table->string('outcome_reason')->nullable(); // سبب الفوز/الخسارة (BP-05 خطوة 6)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_tenders');
    }
};
