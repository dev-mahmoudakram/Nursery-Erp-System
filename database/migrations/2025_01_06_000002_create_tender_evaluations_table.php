<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('government_tender_id')->constrained('government_tenders')->cascadeOnDelete();

            // معايير التقييم (بند ٥.٨ من كراسة الطلب)
            $table->unsignedTinyInteger('expected_margin_percent');
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedTinyInteger('operational_capacity_score'); // 1-5
            $table->unsignedTinyInteger('activity_fit_score'); // 1-5

            $table->enum('system_recommendation', ['bid', 'no_bid'])->nullable(); // توصية آلية أولية
            $table->enum('final_decision', ['bid', 'no_bid'])->nullable(); // قرار الإدارة العليا الفعلي

            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_evaluations');
    }
};
