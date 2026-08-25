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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable()->unique();
            $table->string('photo')->nullable();
            $table->string('position')->nullable(); // Jabatan (Kaprodi,Sekprodi,etc)
            $table->string('academic_title')->nullable(); // Gelar Akademik
            $table->string('functional_position')->nullable(); // Jabatan Fungsional
            $table->string('expertise')->nullable(); // Bidang Keahlian
            $table->string('education')->nullable(); // Pendidikan Terakhir
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('biography')->nullable();
            $table->enum('type', ['dosen', 'tendik'])->default('tendik');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->string('language', 5)->default('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
