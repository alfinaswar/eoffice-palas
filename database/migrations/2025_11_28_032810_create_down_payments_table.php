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
        Schema::create('down_payments', function (Blueprint $table) {
            $table->id();
            $table->string('IdTransaksi')->nullable();
            $table->string('IdBooking')->nullable();
            $table->string('Nomor', 50)->unique();
            $table->string('IdProduk')->nullable();
            $table->string('NamaPelanggan')->nullable();
            $table->date('Tanggal')->nullable();
            $table->string('Total')->nullable();
            $table->string('SisaBayar')->nullable();
            $table->string('JenisPembayaran')->nullable();
            $table->text('Keterangan')->nullable();
            $table->string('Penerima')->nullable();
            $table->dateTime('DiterimaPada')->nullable();
            $table->string('Penyetor')->nullable();
            $table->dateTime('DiserahkanPada')->nullable();
            $table->string('KodeKantor')->nullable();
            $table->string('UserCreated')->nullable();
            $table->string('UserUpdated')->nullable();
            $table->string('UserDeleted')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('down_payments');
    }
};
