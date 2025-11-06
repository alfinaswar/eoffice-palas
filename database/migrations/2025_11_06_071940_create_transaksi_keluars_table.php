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
        Schema::create('transaksi_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('Nomor')->nullable();
            $table->string('Jenis')->nullable();
            $table->string('Jumlah')->nullable();
            $table->string('Total')->nullable();
            $table->text('Keterangan')->nullable();
            $table->date('Tanggal')->nullable();
            $table->string('IdPetugas')->nullable();
            $table->string('KodeKantor', 100)->nullable();
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
        Schema::dropIfExists('transaksi_keluars');
    }
};
