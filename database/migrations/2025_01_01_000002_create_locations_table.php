<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursery_id')->constrained('nurseries')->cascadeOnDelete();
            $table->foreignId('parent_location_id')->nullable()
                ->constrained('locations')->nullOnDelete();
            $table->string('code')->unique(); // رمز موحد للموقع
            // نوع الموقع الهيكلي
            $table->enum('type', ['zone', 'sector', 'row', 'bed', 'internal'])->default('internal');
            // موقع حالة خاصة (تحت الفحص/تحت النمو/جاهز للبيع/محجوز/تحت التجهيز/تالف/معزول/مرتجع)
            $table->enum('status_type', [
                'normal', 'under_inspection', 'growing', 'ready_for_sale',
                'reserved', 'preparing', 'damaged', 'isolated', 'returned',
            ])->default('normal');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
