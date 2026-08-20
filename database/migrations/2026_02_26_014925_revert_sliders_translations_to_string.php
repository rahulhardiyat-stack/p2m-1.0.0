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
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('title_old')->nullable()->after('title');
            $table->string('subtitle_old')->nullable()->after('subtitle');
            $table->string('button_text_old')->nullable()->after('button_text');
        });

        DB::table('sliders')->get()->each(function ($slider) {
            $title = json_decode($slider->title, true);
            $subtitle = json_decode($slider->subtitle, true);
            $button_text = json_decode($slider->button_text, true);

            DB::table('sliders')->where('id', $slider->id)->update([
                'title_old' => is_array($title) ? ($title['id'] ?? array_values($title)[0] ?? '') : $slider->title,
                'subtitle_old' => is_array($subtitle) ? ($subtitle['id'] ?? array_values($subtitle)[0] ?? '') : $slider->subtitle,
                'button_text_old' => is_array($button_text) ? ($button_text['id'] ?? array_values($button_text)[0] ?? '') : $slider->button_text,
            ]);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['title', 'subtitle', 'button_text']);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->renameColumn('title_old', 'title');
            $table->renameColumn('subtitle_old', 'subtitle');
            $table->renameColumn('button_text_old', 'button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->json('title_new')->nullable()->after('title');
            $table->json('subtitle_new')->nullable()->after('subtitle');
            $table->json('button_text_new')->nullable()->after('button_text');
        });

        DB::table('sliders')->get()->each(function ($slider) {
            DB::table('sliders')->where('id', $slider->id)->update([
                'title_new' => json_encode(['id' => $slider->title, 'en' => $slider->title]),
                'subtitle_new' => json_encode(['id' => $slider->subtitle, 'en' => $slider->subtitle]),
                'button_text_new' => json_encode(['id' => $slider->button_text, 'en' => $slider->button_text]),
            ]);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['title', 'subtitle', 'button_text']);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('subtitle_new', 'subtitle');
            $table->renameColumn('button_text_new', 'button_text');
        });
    }
};
