<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('type')->nullable(); // شاحنة صغيرة/كبيرة/ثلاجة...
            $table->decimal('capacity', 10, 2)->nullable(); // بالكيلوجرام أو الوحدة المناسبة
            $table->enum('status', ['available', 'in_use', 'maintenance'])->default('available');
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('license_number')->nullable();
            $table->enum('status', ['available', 'on_route', 'off_duty'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
    }
};
