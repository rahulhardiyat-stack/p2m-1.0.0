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
        // Get all posts and update their title, excerpt, and content
        $posts = DB::table('posts')->get();

        foreach ($posts as $post) {
            $title = $this->extractTranslation($post->title);
            $excerpt = $this->extractTranslation($post->excerpt);
            $content = $this->extractTranslation($post->content);

            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $content,
            ]);
        }
    }

    public function down(): void
    {
        // To rollback, we'd need to convert strings back to JSON arrays
        // For simplicity, we wrap the current string in a JSON object with 'id' key.
        $posts = DB::table('posts')->get();

        foreach ($posts as $post) {
            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                'title' => json_encode(['id' => $post->title, 'en' => $post->title]),
                'excerpt' => json_encode(['id' => $post->excerpt, 'en' => $post->excerpt]),
                'content' => json_encode(['id' => $post->content, 'en' => $post->content]),
            ]);
        }
    }

    private function extractTranslation($jsonString): ?string
    {
        if (empty($jsonString)) {
            return $jsonString;
        }

        $decoded = json_decode($jsonString, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded['id'] ?? array_values($decoded)[0] ?? '';
        }

        return $jsonString;
    }
};
