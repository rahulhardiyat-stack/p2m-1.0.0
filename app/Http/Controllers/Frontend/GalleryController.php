<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::forLocale()->where('is_active', true);

        if ($request->type === 'video') {
            $query->where('type', 'video');
        }
        elseif ($request->type === 'photo') {
            $query->where('type', 'photo');
        }

        $items = $query->orderBy('order')->latest()->paginate(16)->withQueryString();

        return view('frontend.gallery.index', compact('items'));
    }
}
