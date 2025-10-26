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
        Schema::create('Transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('KodeTransaksi', 50)->unique();
            $table->string('IdPenawaran')->nullable();
            $table->string('IdBooking')->nullable();
            $table->string('IdProduk')->nullable();
            $table->date('TanggalTransaksi')->nullable();
            $table->string('IdPelanggan')->nullable();
            $table->string('IdPetugas')->nullable();
            $table->enum('JenisTransaksi', ['Cash', 'Cicilan'])->nullable();
            $table->string('TotalHarga')->default('0')->nullable();
            $table->string('UangMuka')->default('0')->nullable();
            $table->string('SisaBayar')->default('0')->nullable();
            $table->enum('StatusPembayaran', ['Lunas', 'BelumLunas'])->default('BelumLunas');
            $table->text('Keterangan')->nullable();
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
        Schema::dropIfExists('transaksis');
    }
};
