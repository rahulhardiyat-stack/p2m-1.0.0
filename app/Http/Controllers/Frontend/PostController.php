<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category'])
            ->published()
            ->when($request->category, fn($q) => $q->whereHas('category', fn($cq) => $cq->where('id', $request->category)->orWhere('slug', $request->category)))
            ->when($request->tag, fn($q) => $q->whereHas('tags', fn($tq) => $tq->where('id', $request->tag)->orWhere('slug', $request->tag)))
            ->when($request->sdg, fn($q) => $q->whereJsonContains('sdgs', $request->sdg))
            ->when($request->type, fn($q) => $q->byType($request->type))
            ->when($request->search, function ($q) use ($request) {
            $q->where(function ($sq) use ($request) {
                    $search = strtolower($request->search);
                    $sq->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(content) LIKE ?', ["%{$search}%"]);
                }
                );
            })
            ->latest('published_at');

        $posts = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('posts')->orderBy('posts_count', 'desc')->take(10)->get();
        $tags = Tag::withCount('posts')->orderBy('posts_count', 'desc')->take(20)->get();
        $featuredPosts = Post::with(['user', 'category'])->published()->where('is_featured', true)->latest('published_at')->take(3)->get();

        return view('frontend.posts.index', compact('posts', 'categories', 'tags', 'featuredPosts'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['user', 'category', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        $related = Post::with('category')
            ->published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.posts.show', compact('post', 'related'));
    }
}
