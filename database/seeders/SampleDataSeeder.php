<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Slider::updateOrCreate(
            ['title' => 'Selamat Datang di BalaKutaK CMS'],
            [
                'subtitle' => 'BalaKutaK adalah sebuah Content Management System (CMS) berbasis open source yang dirancang untuk memberikan kemudahan, fleksibilitas, dan kebebasan.',
                'image' => 'sliders/default_slider.png',
                'button_text' => 'Pelajari Selengkapnya',
                'button_url' => '/tentang',
                'order' => 1,
                'is_active' => true,
                'language' => 'id'
            ]
        );
    }
}
