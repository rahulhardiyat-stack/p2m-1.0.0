<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accreditation;
use App\Models\Faculty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;


class AccreditationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Matikan Foreign Key Constraint sementara agar truncate/delete aman
        Schema::disableForeignKeyConstraints();

        // Hapus semua data lama
        Accreditation::truncate();

        // Aktifkan kembali Foreign Key Constraint
        Schema::enableForeignKeyConstraints();

        // Ambil ID fakultas berdasarkan nama/slug
        $stain = Faculty::where('slug', Str::slug('STAIN Teungku Dirundeng meulaboh'))->first();
        $tar = Faculty::where('slug', Str::slug('Tarbiyah dan Keguruan'))->first();
        $sya = Faculty::where('slug', Str::slug('Syariah'))->first();
        $dak = Faculty::where('slug', Str::slug('Dakwah dan Komunikasi'))->first();


        $accreditations = [
            [
                'type' => 'Program Studi',
                'title' => 'Pendidikan Agama Islam',
                'faculty_id' => $tar?->id,
                'degree' => 'S1',
                'status' => 'Baik Sekali',
                'issued_date' => '2026-07-21',
                'expired_date' => '2031-07-21',
                'sk_number' => 'SK/BAN-PT/BKI/2026',
                'document_path' => 'accreditations/sk-bki.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Pendidikan Profesi Guru Keagamaan',
                'faculty_id' => $tar?->id,
                'degree' => 'profesi',
                'status' => 'Baik Sekali',
                'issued_date' => '2026-06-10',
                'expired_date' => '2031-06-09',
                'sk_number' => 'SK/LAMDIK/PPGK/2026',
                'document_path' => 'accreditations/sk-ppgk.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Hukum Tata Negara',
                'faculty_id' => $sya?->id,
                'degree' => 'S1',
                'status' => 'Baik Sekali',
                'issued_date' => '2026-06-02',
                'expired_date' => '2031-06-02',
                'sk_number' => 'SK/BAN-PT/HTN/2026',
                'document_path' => 'accreditations/sk-htn.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Bimbingan dan Konseling Pendidikan Islam',
                'faculty_id' => $dak?->id,
                'degree' => 'S1',
                'status' => 'Baik',
                'issued_date' => '2021-05-07',
                'expired_date' => '2026-05-06', // Contoh status kadaluarsa
                'sk_number' => 'SK/BAN-PT/BKPI/2021',
                'document_path' => 'accreditations/sk-bkpi.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Komunikasi dan Penyiaran Islam',
                'faculty_id' => $dak?->id,
                'degree' => 'S1',
                'status' => 'Unggul',
                'issued_date' => '2026-03-31',
                'expired_date' => '2031-03-31',
                'sk_number' => 'SK/BAN-PT/KPI/2026',
                'document_path' => 'accreditations/sk-kpi.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Hukum Keluarga Islam',
                'faculty_id' => $sya?->id,
                'degree' => 'S2',
                'status' => 'Unggul',
                'issued_date' => '2025-12-18',
                'expired_date' => '2030-12-18',
                'sk_number' => 'SK/BAN-PT/HKI-S2/2025',
                'document_path' => 'accreditations/sk-hki-s2.pdf',
            ],
            [
                'type' => 'Program Studi',
                'title' => 'Ekonomi Syariah',
                'faculty_id' => $sya?->id,
                'degree' => 'S1',
                'status' => 'Baik Sekali',
                'issued_date' => '2025-05-09',
                'expired_date' => '2030-05-09',
                'sk_number' => 'SK/LAMEMBA/ES/2025',
                'document_path' => 'accreditations/sk-es.pdf',
            ],
            [
                'type' => 'institution',
                'title' => 'STAIN Teungku Dirundeng Meulaboh',
                'faculty_id' => $stain?->id, // Tipe Institusi tidak memiliki fakultas
                'degree' => null,
                'status' => 'Baik Sekali',
                'issued_date' => '2024-01-15',
                'expired_date' => '2029-01-15',
                'sk_number' => 'SK/BAN-PT/INST/2024',
                'document_path' => 'accreditations/sk-institusi.pdf',
            ],
        ];

        foreach ($accreditations as $item) {
            Accreditation::create($item);
        }
    }
}
