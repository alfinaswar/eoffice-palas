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
        Schema::create('master_grades', function (Blueprint $table) {
            $table->id();
            $table->string('Nama')->nullable();
            $table->string('Keterangan')->nullable();
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
        Schema::dropIfExists('master_grades');
    }
};
