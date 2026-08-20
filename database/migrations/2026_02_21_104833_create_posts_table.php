<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('type', ['post', 'news', 'research', 'community'])->default('post');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('language', 5)->default('id');
            $table->json('seo_meta')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
            $table->index('type');
            $table->index('language');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
