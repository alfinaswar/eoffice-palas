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
        Schema::table('Transaksi', function (Blueprint $table) {
            $table->string('KodeKantor')->nullable()->after('id');
            $table->enum('StatusOrder', ['Aktif', 'Cancel'])->default('Aktif')->after('Keterangan');
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
        Schema::table('transaksi', function (Blueprint $table) {
            //
        });
    }
};
