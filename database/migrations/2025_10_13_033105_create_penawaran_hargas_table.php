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
        Schema::create('penawaran_hargas', function (Blueprint $table) {
            $table->id();
            $table->string('Nomor', 50)->unique();
            $table->date('Tanggal')->nullable();
            $table->string('NamaPelanggan')->nullable();
            $table->string('Total')->nullable();
            $table->text('Keterangan')->nullable();

            $table->enum('StatusAcc1', ['Y', 'N'])->nullable();
            $table->string('DisetujuiPada1')->nullable();
            $table->string('DisetujuiOleh1')->nullable();
            $table->enum('StatusAcc2', ['Y', 'N'])->nullable();
            $table->string('DisetujuiPada2')->nullable();
            $table->string('DisetujuiOleh2')->nullable();
            $table->enum('StatusAcc3', ['Y', 'N'])->nullable();
            $table->string('DisetujuiPada3')->nullable();
            $table->string('DisetujuiOleh3')->nullable();
            $table->enum('StatusAcc4', ['Y', 'N'])->nullable();
            $table->string('DisetujuiPada4')->nullable();
            $table->string('DisetujuiOleh4')->nullable();
            $table->string('DiajukanOleh')->nullable();
            $table->string('DiajukanPada')->nullable();
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
        Schema::dropIfExists('penawaran_hargas');
    }
};
