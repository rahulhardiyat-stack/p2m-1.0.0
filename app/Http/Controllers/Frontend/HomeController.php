<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Slider;
use App\Models\Lecturer;
use App\Models\Announcement;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\VisitorLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Log visitor
        VisitorLog::create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'referer' => request()->header('referer'),
            'visited_date' => today(),
        ]);

        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $latestNews = Post::with('category')->published()->whereIn('type', ['news', 'post'])->latest('published_at')->take(5)->get();
        $latestArticles = Post::with('category')->published()->byType('post')->latest('published_at')->take(3)->get();
        $locale = app()->getLocale();
        $fallbackLocale = 'id';
        $latestVideoQuery = Gallery::with('event')
            ->where('is_active', true)
            ->where('type', 'video')
            ->whereNotNull('youtube_id');
        $hasLocaleVideos = (clone $latestVideoQuery)->where('language', $locale)->exists();

        $latestVideos = $latestVideoQuery
            ->where(function ($q) use ($locale, $fallbackLocale, $hasLocaleVideos) {
                if ($hasLocaleVideos) {
                    $q->where('language', $locale);
                    return;
                }

                $q->where('language', $fallbackLocale)
                    ->orWhereNull('language')
                    ->orWhere('language', '');
            })
            ->orderBy('order')
            ->latest()
            ->take(8)
            ->get();
        $upcomingEvents = Event::where('is_published', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(4)
            ->get();

        // Fallback: if no upcoming events, show the most recent past events
        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = Event::where('is_published', true)
                ->orderBy('start_date', 'desc')
                ->take(4)
                ->get();
        }
        // Removed forLocale() grouping
        $announcements = Announcement::where('is_published', true)
            ->where(fn($q) => $q->whereNull('expire_date')->orWhere('expire_date', '>=', today()))
            ->latest()
            ->take(5)
            ->get();
        $testimonials = Testimonial::where('is_approved', true)->latest()->take(6)->get();
        $partners = Partner::where('is_active', true)->orderBy('order')->get();

        $activeInfographic = \App\Models\Infographic::where('is_active', true)->first();

        // Prepare dynamic stats
        $stats = [];
        if ($activeInfographic && is_array($activeInfographic->stats)) {
            $stats = $activeInfographic->stats;
        }
        else {
            // Fallback default stats if none active
            $stats = [
                ['label' => __('menu.active_students'), 'value' => 1200],
                ['label' => __('menu.lecturers'), 'value' => Lecturer::active()->dosen()->count()],
                ['label' => __('menu.alumni'), 'value' => 0],
                ['label' => __('menu.research'), 'value' => Post::published()->byType('research')->count()],
            ];
        }

        return view('frontend.home', compact(
            'sliders', 'latestNews', 'latestArticles', 'upcomingEvents',
            'announcements', 'testimonials', 'partners', 'stats', 'activeInfographic',
            'latestVideos'
        ));
    }

    public function showAnnouncement($slug)
    {
        $announcement = Announcement::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.announcements.show', compact('announcement'));
    }

    public function sitemap()
    {
        $posts = Post::published()->latest('published_at')->get(['slug', 'updated_at']);
        $events = Event::where('is_published', true)->get(['slug', 'updated_at']);
        return response()->view('frontend.sitemap', compact('posts', 'events'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nDisallow: /admin/\nSitemap: " . url('/sitemap.xml');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
