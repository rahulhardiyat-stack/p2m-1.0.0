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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->text('content_old')->nullable()->after('content');
        });

        DB::table('testimonials')->get()->each(function ($testimonial) {
            $content = json_decode($testimonial->content, true);
            DB::table('testimonials')->where('id', $testimonial->id)->update([
                'content_old' => is_array($content) ? ($content['id'] ?? array_values($content)[0] ?? '') : $testimonial->content,
            ]);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('content');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('content_old', 'content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to translatable string (simplified fallback schema)
        Schema::table('testimonials', function (Blueprint $table) {
            $table->json('content_new')->nullable()->after('content');
        });

        DB::table('testimonials')->get()->each(function ($testimonial) {
            DB::table('testimonials')->where('id', $testimonial->id)->update([
                'content_new' => json_encode(['id' => $testimonial->content, 'en' => $testimonial->content]),
            ]);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('content');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('content_new', 'content');
        });
    }
};
