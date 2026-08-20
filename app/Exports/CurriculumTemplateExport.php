<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class CurriculumTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([
            [
                'IF101',
                'Pemrograman Dasar',
                '1',
                '3',
                'Belajar dasar-dasar pemrograman menggunakan Python',
                'wajib',
                'Informatika',
                '1'
            ],
            [
                'IF202',
                'Struktur Data',
                '2',
                '4',
                'Belajar tentang Array, Linked List, Tree, dan Graph',
                'wajib',
                'Informatika',
                '1'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'semester',
            'sks',
            'deskripsi',
            'tipe',
            'konsentrasi',
            'aktif'
        ];
    }

    public function title(): string
    {
        return 'Template Import Kurikulum';
    }
}
