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
        Schema::create('progres_pengurusan_surat_tanahs', function (Blueprint $table) {
            $table->id();
            $table->string('KodeProyek', 100)->nullable();
            $table->string('KodeKantor', 100)->nullable();
            $table->date('Tanggal')->nullable();
            $table->string('Deskripsi')->nullable();
            $table->string('Legal')->nullable();
            $table->string('NamaBank')->nullable();
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
        Schema::dropIfExists('progres_pengurusan_surat_tanahs');
    }
};
