<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('academic_year'); // e.g., 2024/2025
            $table->string('semester'); // Ganjil, Genap, Antara
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('type')->default('kegiatan'); // kegiatan, ujian, libur, pendaftaran
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // For calendar view color coding
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
