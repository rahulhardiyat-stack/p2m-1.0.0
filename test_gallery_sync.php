<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Str;

$user = User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}

auth()->login($user);

$title = "Test Post " . time();
$post = Post::create([
    'title' => $title,
    'slug' => Str::slug($title),
    'type' => 'post',
    'status' => 'published',
    'user_id' => $user->id,
    'content' => 'Test content',
]);

echo "Post created: {$post->id}\n";

$path = "testing/image.jpg";
$post->update(['featured_image' => $path]);

echo "Updating gallery...\n";
Gallery::updateOrCreate(
    ['title' => $post->title, 'album' => 'Artikel'],
    [
        'type' => 'photo',
        'file_path' => $path,
        'is_active' => true,
        'language' => 'id',
    ]
);

$check = Gallery::where('title', $title)->first();
if ($check) {
    echo "Gallery entry created! ID: {$check->id}\n";
} else {
    echo "FAILED to create gallery entry\n";
}
