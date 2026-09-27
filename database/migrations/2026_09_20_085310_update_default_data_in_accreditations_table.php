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
        Schema::table('accreditations', function (Blueprint $table) {
            // 1. Ubah struktur enum agar menerima pilihan baru, dan ganti default-nya
            $table->enum('type', ['program', 'program studi', 'institution'])
                ->default('program studi')
                ->change();
        });

        // 2. Update data lama dari 'program' menjadi 'program studi'
        DB::table('accreditations')
            ->where('type', 'program')
            ->update(['type' => 'program studi']);

        Schema::table('accreditations', function (Blueprint $table) {
            // 3. (Opsional) Hapus pilihan 'program' yang lama agar enum kembali bersih
            $table->enum('type', ['program studi', 'institution'])
                ->default('program studi')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Izinkan 'program' kembali
        Schema::table('accreditations', function (Blueprint $table) {
            $table->enum('type', ['program', 'program studi', 'institution'])
                ->default('program')
                ->change();
        });

        // Kembalikan data
        DB::table('accreditations')
            ->where('type', 'program studi')
            ->update([
                'type' => 'program',
            ]);

        // Kembalikan enum seperti semula
        Schema::table('accreditations', function (Blueprint $table) {
            $table->enum('type', ['program', 'institution'])
                ->default('program')
                ->change();
        });
    }
};
