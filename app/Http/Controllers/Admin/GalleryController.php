<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Event;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('event')
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->album, fn($q) => $q->where('album', $request->album));

        $galleries = $query->latest()->paginate(15)->withQueryString();

        $albums = Gallery::select('album')->whereNotNull('album')->distinct()->pluck('album');

        return view('admin.gallery.index', compact('galleries', 'albums'));
    }

    public function create()
    {
        $events = Event::latest()->get();
        return view('admin.gallery.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:photo,video',
            'caption' => 'nullable|string',
            'album' => 'nullable|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'order' => 'nullable|integer',
            'language' => 'nullable|in:id,en',
            'is_active' => 'boolean',
        ]);

        $gallery = new Gallery($validated);
        $gallery->language = $validated['language'] ?? app()->getLocale();
        $gallery->is_active = $request->boolean('is_active', true);

        if ($request->type === 'photo') {
            $request->validate(['file_path' => 'required|image|max:10240']);
            $file = $request->file('file_path');
            $gallery->file_path = $file->store('gallery', 'public');
            
            // Register in Media Library
            Media::updateOrCreate(
                ['path' => $gallery->file_path],
                [
                    'user_id' => auth()->id() ?? 1,
                    'filename' => basename($gallery->file_path),
                    'original_name' => $file->getClientOriginalName(),
                    'url' => asset('storage/' . $gallery->file_path),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'folder' => 'gallery'
                ]
            );
        }
        else {
            $request->validate(['youtube_url' => 'required|url']);
            $gallery->youtube_url = $request->youtube_url;

            // Extract Youtube ID
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $request->youtube_url, $matches);
            $gallery->youtube_id = $matches[1] ?? null;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')->with('success', 'Media berhasil ditambahkan ke galeri!');
    }

    public function edit(Gallery $gallery)
    {
        $events = Event::latest()->get();
        return view('admin.gallery.edit', compact('gallery', 'events'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:photo,video',
            'caption' => 'nullable|string',
            'album' => 'nullable|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'order' => 'nullable|integer',
            'language' => 'nullable|in:id,en',
            'is_active' => 'boolean',
        ]);

        if ($request->type === 'photo') {
            if ($request->hasFile('file_path')) {
                $request->validate(['file_path' => 'image|max:10240']);
                if ($gallery->file_path) {
                    Storage::disk('public')->delete($gallery->file_path);
                    Media::where('path', $gallery->file_path)->delete();
                }
                $file = $request->file('file_path');
                $validated['file_path'] = $file->store('gallery', 'public');
                
                // Update in Media Library
                Media::create([
                    'user_id' => auth()->id() ?? 1,
                    'filename' => basename($validated['file_path']),
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $validated['file_path'],
                    'url' => asset('storage/' . $validated['file_path']),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'folder' => 'gallery'
                ]);
            }
            // Clear video fields if switching type
            $validated['youtube_url'] = null;
            $validated['youtube_id'] = null;
        }
        else {
            $request->validate(['youtube_url' => 'required|url']);
            $validated['youtube_url'] = $request->youtube_url;

            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $request->youtube_url, $matches);
            $validated['youtube_id'] = $matches[1] ?? null;

            // Clear photo fields if switching type
            if ($gallery->file_path && $gallery->type === 'photo') {
                Storage::disk('public')->delete($gallery->file_path);
                $validated['file_path'] = null;
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Item galeri berhasil diperbarui!');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->file_path) {
            Storage::disk('public')->delete($gallery->file_path);
        }
        $gallery->delete();

        return back()->with('success', 'Item galeri berhasil dihapus!');
    }

    public function massDestroy(Request $request)
    {
        if (!auth()->user()->hasRole(['Super Admin', 'Admin Prodi'])) {
            abort(403, 'Aksi ini hanya untuk Super Admin dan Admin.');
        }

        $ids = $request->ids;
        if (empty($ids)) {
            return back()->with('error', 'Pilih item galeri yang ingin dihapus!');
        }

        $items = Gallery::whereIn('id', $ids)->get();

        foreach ($items as $item) {
            if ($item->file_path) {
                Storage::disk('public')->delete($item->file_path);
            }
            $item->delete();
        }

        return back()->with('success', count($ids) . ' item galeri berhasil dihapus masal!');
    }
}
