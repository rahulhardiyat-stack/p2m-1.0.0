<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Event;
use App\Models\Lecturer;
use App\Models\ContactMessage;
use App\Models\VisitorLog;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recentPosts = Post::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->where('is_published', true)
            ->orderBy('start_date')
            ->take(5)
            ->get();
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        // Chart data: visitors last 30 days
        $visitorChart = VisitorLog::select(
            DB::raw('DATE(visited_date) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('visited_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d M');
            $chartLabels[] = $label;
            $chartData[] = $visitorChart[$date]->count ?? 0;
        }

        $popularPosts = Post::published()
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentPosts', 'upcomingEvents',
            'unreadMessages', 'recentMessages',
            'chartLabels', 'chartData', 'popularPosts'
        ));
    }

    private function getStats(): array
    {
        return [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'total_lecturers' => Lecturer::count(),
            'total_events' => Event::count(),
            'today_visitors' => VisitorLog::whereDate('visited_date', today())->count(),
            'month_visitors' => VisitorLog::whereMonth('visited_date', now()->month)->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'active_announcements' => Announcement::where('is_published', true)
            ->where(function ($q) {
            $q->whereNull('expire_date')->orWhere('expire_date', '>=', today()); })
            ->count(),
        ];
    }

    public function stats()
    {
        // AJAX endpoint for live stats
        return response()->json($this->getStats());
    }
}
