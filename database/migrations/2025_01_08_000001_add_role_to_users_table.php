<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // مطابق لمصفوفة الصلاحيات في وثيقة Security & Infrastructure
            $table->enum('role', [
                'admin',            // الإدارة العليا
                'nursery_manager',  // مدير المشتل
                'inventory_keeper', // أمين المخزون
                'sales_rep',        // مندوب المبيعات
                'accountant',       // المحاسبة
            ])->default('sales_rep')->after('email');

            $table->foreignId('nursery_id')->nullable()->constrained('nurseries')->nullOnDelete()
                ->after('role'); // يقيّد مدير/أمين المشتل بمشتله فقط عند الحاجة لاحقًا
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('nursery_id');
            $table->dropColumn('role');
        });
    }
};
