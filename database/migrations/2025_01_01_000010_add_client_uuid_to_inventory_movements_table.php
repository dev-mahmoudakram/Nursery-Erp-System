<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // يولَّده تطبيق الجوال محليًا أثناء انقطاع الاتصال، ويُستخدم لمنع تكرار المزامنة (Idempotency Key)
            $table->uuid('client_uuid')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn('client_uuid');
        });
    }
};
