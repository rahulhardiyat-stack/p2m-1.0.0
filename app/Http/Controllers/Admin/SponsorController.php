<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $sponsors = Sponsor::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('admin.sponsors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $sponsor = new Sponsor();
        $sponsor->name = $validated['name'];
        $sponsor->url = $validated['url'] ?? null;
        $sponsor->order = $validated['order'] ?? 0;
        $sponsor->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $sponsor->logo = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->save();

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil ditambahkan!');
    }

    public function show(Sponsor $sponsor)
    {
        return redirect()->route('admin.sponsors.edit', $sponsor);
    }

    public function edit(Sponsor $sponsor)
    {
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $validated['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update([
            'name' => $validated['name'],
            'url' => $validated['url'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'logo' => $validated['logo'] ?? $sponsor->logo,
        ]);

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil diperbarui!');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo) {
            Storage::disk('public')->delete($sponsor->logo);
        }
        $sponsor->delete();

        return back()->with('success', 'Sponsor berhasil dihapus!');
    }

    public function toggleStatus(Sponsor $sponsor)
    {
        $sponsor->update(['is_active' => !$sponsor->is_active]);
        return back()->with('success', 'Status sponsor berhasil diubah!');
    }
}
