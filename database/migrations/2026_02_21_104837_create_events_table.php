<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('location')->nullable();
            $table->string('online_url')->nullable();
            $table->string('featured_image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->integer('max_participants')->nullable();
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 15, 2)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('organizer')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('registration_url')->nullable();
            $table->string('language', 5)->default('id');
            $table->boolean('is_published')->default(false);
            $table->json('seo_meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
