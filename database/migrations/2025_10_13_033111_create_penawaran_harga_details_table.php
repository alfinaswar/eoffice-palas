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
        Schema::create('penawaran_harga_details', function (Blueprint $table) {
            $table->id();
            $table->String('IdPenawaran');
            $table->String('IdProduk');
            $table->integer('Jumlah')->default(1);
            $table->String('Harga')->default(0);
            $table->String('Subtotal')->default(0);
            $table->String('Diskon')->default(0);
            $table->enum('JenisDiskon', ['Rp', 'Persen'])->nullable();
            $table->String('Total')->default(0);
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
        Schema::dropIfExists('penawaran_harga_details');
    }
};
