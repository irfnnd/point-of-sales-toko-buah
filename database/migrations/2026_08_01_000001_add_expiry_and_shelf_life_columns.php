<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fruits', function (Blueprint $table) {
            $table->unsignedInteger('shelf_life_days')->nullable()->after('selling_price')->comment('Estimasi masa simpan dalam hari');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable()->after('recorded_at')->comment('Tanggal kedaluwarsa stok masuk');
        });
    }

    public function down(): void
    {
        Schema::table('fruits', function (Blueprint $table) {
            $table->dropColumn('shelf_life_days');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('expired_at');
        });
    }
};
