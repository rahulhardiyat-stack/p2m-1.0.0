<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('user')
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->status === 'published', fn($q) => $q->where('is_published', true))
            ->when($request->status === 'draft', fn($q) => $q->where('is_published', false));

        if (!auth()->user()->hasRole(['Super Admin', 'Admin Prodi'])) {
            $query->where('user_id', auth()->id());
        }

        $announcements = $query->latest()->paginate(15)->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_published' => 'boolean',
            'expire_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $announcement = new Announcement($validated);
        $announcement->user_id = auth()->id();
        $announcement->is_published = $request->boolean('is_published');
        $announcement->slug = \Illuminate\Support\Str::slug($request->title) . '-' . \Illuminate\Support\Str::random(5);

        if ($request->hasFile('attachment')) {
            $announcement->attachment = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement->save();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_published' => 'boolean',
            'expire_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }
        else {
            unset($validated['attachment']);
        }

        $validated['is_published'] = $request->boolean('is_published');

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus!');
    }
}
