<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class LecturerTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([
            [
                'Dr. Budi Santoso, M.Kom',
                '198001012010121001',
                '0001018001',
                'dosen',
                'Lektor Kepala',
                'Ketua Program Studi',
                'Dosen Analisis Data',
                'Data Mining, Machine Learning',
                'S3 Ilmu Komputer - UI',
                'budi@kampus.ac.id',
                '08123456789',
                'https://scholar.google.com/citations?user=xyz',
                'https://sinta.kemdikbud.go.id/authors/profile/123',
                'https://garuda.kemdikbud.go.id/author/view/456',
                'https://linkedin.com/in/budisantoso',
                'https://budisantoso.blog',
                'Seorang dosen yang berdedikasi...'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIP',
            'NIDN',
            'Tipe (dosen/tendik)',
            'Jabatan Akademik',
            'Jabatan Struktural',
            'Jabatan Umum',
            'Keahlian',
            'Pendidikan',
            'Email',
            'Phone',
            'Google Scholar URL',
            'SINTA URL',
            'Garuda URL',
            'LinkedIn URL',
            'Website URL',
            'Biografi'
        ];
    }

    public function title(): string
    {
        return 'Template Import Dosen Staff';
    }
}
