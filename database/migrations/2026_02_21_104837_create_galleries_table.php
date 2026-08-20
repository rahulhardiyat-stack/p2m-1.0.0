<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('file_path')->nullable(); // for photo
            $table->string('thumbnail')->nullable();
            $table->string('youtube_url')->nullable(); // for video
            $table->string('youtube_id', 30)->nullable(); // parsed from URL
            $table->text('caption')->nullable();
            $table->string('album')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
