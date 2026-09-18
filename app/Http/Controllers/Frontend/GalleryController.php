<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $fallbackLocale = 'id';

        $query = Gallery::where('is_active', true);

        if ($request->type === 'video') {
            $query->where('type', 'video');
        }
        elseif ($request->type === 'photo') {
            $query->where('type', 'photo');
        }

        $hasLocaleItems = (clone $query)->where('language', $locale)->exists();

        $query->where(function ($q) use ($locale, $fallbackLocale, $hasLocaleItems) {
            if ($hasLocaleItems) {
                $q->where('language', $locale);
                return;
            }

            $q->where('language', $fallbackLocale)
                ->orWhereNull('language')
                ->orWhere('language', '');
        });

        $items = $query->orderBy('order')->latest()->paginate(16)->withQueryString();

        return view('frontend.gallery.index', compact('items'));
    }
}
