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
        Schema::create('transaksi_keuangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('KodeKantor', 200)->nullable();
            $table->date('Tanggal')->nullable();
            $table->enum('Jenis', ['IN', 'OUT'])->nullable();
            $table->string('Kategori', 200)->nullable();
            $table->text('Deskripsi')->nullable();
            $table->string('Nominal', 200)->nullable();
            $table->string('NamaBank')->nullable();
            $table->string('RefType', 50)->nullable();
            $table->string('RefId')->nullable();
            $table->string('SaldoSetelah', 200)->nullable();
            $table->string('UserCreate', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangans');
    }
};
