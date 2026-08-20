<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Akreditasi', 'slug' => 'akreditasi', 'description' => 'Dokumen terkait akreditasi prodi dan institusi.'],
            ['name' => 'Pedoman & Panduan', 'slug' => 'pedoman-panduan', 'description' => 'Buku pedoman akademik, panduan teknis, dll.'],
            ['name' => 'Kurikulum', 'slug' => 'kurikulum', 'description' => 'Dokumen struktur kurikulum dan silabus.'],
            ['name' => 'Peraturan & SK', 'slug' => 'peraturan-sk', 'description' => 'Surat Keputusan dan peraturan resmi.'],
            ['name' => 'Laporan & Evaluasi', 'slug' => 'laporan-evaluasi', 'description' => 'Laporan tahunan, evaluasi diri, dll.'],
            ['name' => 'Formulir & Template', 'slug' => 'formulir-template', 'description' => 'Formulir pengajuan, template surat, dll.'],
            ['name' => 'Rencana Strategis', 'slug' => 'renstra', 'description' => 'Rencana strategis dan operasional.'],
            ['name' => 'Publikasi & Riset', 'slug' => 'publikasi-riset', 'description' => 'Dokumen hasil penelitian dan publikasi.'],
            ['name' => 'Umum', 'slug' => 'umum', 'description' => 'Dokumen umum lainnya.'],
        ];

        foreach ($categories as $category) {
            \App\Models\DocumentCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
