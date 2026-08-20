<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 20); // pdf, doc, xlsx, etc.
            $table->unsignedBigInteger('file_size')->default(0); // bytes
            $table->string('category')->default('umum'); // akreditasi, pedoman, laporan, dll
            $table->unsignedInteger('download_count')->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
