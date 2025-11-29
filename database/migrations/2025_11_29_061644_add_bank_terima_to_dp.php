<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('down_payments', function (Blueprint $table) {
            $table->string('DariBank')->nullable()->after('NamaBank');
            $table->string('NoRekening')->nullable()->after('DariBank');
            $table->string('Bukti')->nullable()->after('Keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('down_paymanets', function (Blueprint $table) {
            //
        });
    }
};
