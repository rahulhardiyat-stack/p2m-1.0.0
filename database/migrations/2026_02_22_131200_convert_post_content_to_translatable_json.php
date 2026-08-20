<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->json('title_new')->nullable()->after('title');
            $table->json('excerpt_new')->nullable()->after('excerpt');
            $table->json('content_new')->nullable()->after('content');
        });

        // Migrate data
        DB::table('posts')->get()->each(function ($post) {
            DB::table('posts')->where('id', $post->id)->update([
                'title_new' => json_encode(['id' => $post->title, 'en' => $post->title]),
                'excerpt_new' => json_encode(['id' => $post->excerpt, 'en' => $post->excerpt]),
                'content_new' => json_encode(['id' => $post->content, 'en' => $post->content]),
            ]);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'content']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('excerpt_new', 'excerpt');
            $table->renameColumn('content_new', 'content');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_old')->nullable()->after('title');
            $table->text('excerpt_old')->nullable()->after('excerpt');
            $table->longText('content_old')->nullable()->after('content');
        });

        // Migrate data back (take id version)
        DB::table('posts')->get()->each(function ($post) {
            $title = json_decode($post->title, true);
            $excerpt = json_decode($post->excerpt, true);
            $content = json_decode($post->content, true);

            DB::table('posts')->where('id', $post->id)->update([
                'title_old' => $title['id'] ?? '',
                'excerpt_old' => $excerpt['id'] ?? '',
                'content_old' => $content['id'] ?? '',
            ]);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'content']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('title_old', 'title');
            $table->renameColumn('excerpt_old', 'excerpt');
            $table->renameColumn('content_old', 'content');
        });
    }
};
