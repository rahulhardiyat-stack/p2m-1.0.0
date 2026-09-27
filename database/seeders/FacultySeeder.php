<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculty;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FacultySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Faculty::truncate();

        Schema::enableForeignKeyConstraints();

        $faculties = [
            'STAIN Teungku Dirundeng Meulaboh',
            'Tarbiyah dan Keguruan',
            'Syariah',
            'Dakwah dan Komunikasi'
        ];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty,
                'slug' => Str::slug($faculty),
            ]);
        }
    }
}
