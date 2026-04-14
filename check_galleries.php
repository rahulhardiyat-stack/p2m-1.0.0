<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gallery;

$galleries = Gallery::where('album', 'Artikel')->get();
echo "Count: " . $galleries->count() . "\n";
foreach ($galleries as $g) {
    echo "ID: {$g->id}, Title: {$g->title}, Path: {$g->file_path}, Active: {$g->is_active}, Lang: {$g->language}\n";
}
