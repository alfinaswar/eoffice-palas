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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->string('IdTransaksi')->nullable();
            $table->string('KodeBayar')->nullable()->unique();
            $table->string('IdPelanggan')->nullable();
            $table->string('CicilanKe')->nullable();
            $table->string('BesarCicilan')->nullable();
            $table->string('TotalPembayaran')->nullable();
            $table->string('DibayarOleh')->nullable();
            $table->string('DibayarPada')->nullable();
            $table->enum('Status', ['Lunas', 'Tidak'])->nullable()->default('Tidak');
            $table->string('UserCreated')->nullable();
            $table->string('UserUpdated')->nullable();
            $table->string('UserDeleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
