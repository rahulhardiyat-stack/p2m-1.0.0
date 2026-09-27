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
        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['program', 'institution'])->default('program'); // Program Studi / Institusi
            $table->string('title'); // Nama Prodi atau Institusi
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->enum('degree', ['D3', 'S1', 'S2', 'S3', 'profesi'])->nullable(); // Jenjang
            $table->enum('status', ['Unggul', 'Baik Sekali', 'Baik', 'A', 'B', 'C']); // Status Akreditasi
            $table->date('issued_date'); // Tanggal SK
            $table->date('expired_date'); // Berlaku Hingga
            $table->string('sk_number')->nullable(); // Nomor SK
            $table->string('document_path')->nullable(); // File Sertifikat/SK (PDF)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accreditations');
    }
};
