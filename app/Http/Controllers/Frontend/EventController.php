<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published();

        if ($request->status === 'past') {
            $query->where('start_date', '<', now())->orderBy('start_date', 'desc');
        }
        else {
            $query->where('start_date', '>=', now())->orderBy('start_date', 'asc');
        }

        $events = $query->paginate(12)->withQueryString();

        return view('frontend.events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        abort_unless($event->is_published, 404);

        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now())
            ->take(3)
            ->get();

        return view('frontend.events.show', compact('event', 'relatedEvents'));
    }
}
