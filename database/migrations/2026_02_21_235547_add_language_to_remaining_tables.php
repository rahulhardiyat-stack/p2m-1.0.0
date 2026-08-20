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
        $tables = ['sliders', 'galleries', 'testimonials', 'documents'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('language', 5)->default('id')->after('id');
                $table->index('language');
            });
        }
    }

    public function down(): void
    {
        $tables = ['sliders', 'galleries', 'testimonials', 'documents'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('language');
            });
        }
    }
};
