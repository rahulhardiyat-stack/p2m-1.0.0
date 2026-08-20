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
        Schema::table('infographics', function (Blueprint $table) {
            if (Schema::hasColumn('infographics', 'academic_year') && !Schema::hasColumn('infographics', 'title')) {
                $table->renameColumn('academic_year', 'title');
            }
        });

        Schema::table('infographics', function (Blueprint $table) {
            if (!Schema::hasColumn('infographics', 'stats')) {
                $table->json('stats')->after('title')->nullable();
            }

            $oldColumns = ['total_students', 'total_alumni', 'total_lecturers', 'total_research'];
            foreach ($oldColumns as $col) {
                if (Schema::hasColumn('infographics', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infographics', function (Blueprint $table) {
            $table->renameColumn('title', 'academic_year');
            $table->integer('total_students')->default(0);
            $table->integer('total_alumni')->default(0);
            $table->integer('total_lecturers')->nullable();
            $table->integer('total_research')->nullable();
            $table->dropColumn('stats');
        });
    }
};
