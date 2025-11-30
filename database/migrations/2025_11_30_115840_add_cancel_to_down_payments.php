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
            $table->enum('StatusOrder', ['Aktif', 'Cancel'])->default('Aktif')->after('Bukti');
            $table->string('UserCancel')->nullable()->after('StatusOrder');
            $table->dateTime('TanggalCancel')->nullable()->after('UserCancel');
            $table->text('AlasanCancel')->nullable()->after('TanggalCancel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('down_payments', function (Blueprint $table) {
            //
        });
    }
};
