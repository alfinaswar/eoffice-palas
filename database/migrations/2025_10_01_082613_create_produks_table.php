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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('Kode')->nullable();
            $table->string('Nama')->nullable();
            $table->string('Grade')->nullable();
            $table->string('Jenis')->nullable();
            $table->string('Proyek')->nullable();
            $table->string('Luas')->nullable();
            $table->string('HargaPerMeter')->nullable();
            $table->string('HargaKredit')->nullable();
            $table->string('Dp')->nullable();
            $table->string('BesarAngsuran')->nullable();
            $table->string('Diskon')->default(0);
            $table->string('HargaNormal')->default(0);
            $table->string('HargaDiskon')->default(0);
            $table->string('Keterangan')->nullable();
            $table->enum('Status', ['Y', 'N'])->nullable()->default('Y');
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
        Schema::dropIfExists('produks');
    }
};
