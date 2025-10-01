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
        Schema::create('master_kantors', function (Blueprint $table) {
            $table->id();
            $table->string('Nama')->nullable();
            $table->string('Kode')->nullable()->unique();
            $table->string('Alamat')->nullable()->unique();
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
        Schema::dropIfExists('master_kantors');
    }
};
