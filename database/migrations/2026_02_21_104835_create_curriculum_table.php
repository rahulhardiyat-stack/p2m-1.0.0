<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curriculum', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name');
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('credits'); // SKS
            $table->text('description')->nullable();
            $table->string('rps_file')->nullable(); // Upload PDF RPS
            $table->enum('type', ['wajib', 'pilihan'])->default('wajib');
            $table->string('concentration')->nullable(); // Konsentrasi/peminatan
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum');
    }
};
