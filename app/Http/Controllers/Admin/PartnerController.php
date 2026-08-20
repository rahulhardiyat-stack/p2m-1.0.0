<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'website_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $partner = new Partner();
        $partner->name = $validated['name'];
        $partner->website_url = $validated['website_url'] ?? null;
        $partner->description = $validated['description'] ?? null;
        $partner->order = $validated['order'] ?? 0;
        $partner->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $partner->logo = $request->file('logo')->store('partners', 'public');
        }

        $partner->save();

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil ditambahkan!');
    }

    public function show(Partner $partner)
    {
        return redirect()->route('admin.partners.edit', $partner);
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'website_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update([
            'name' => $validated['name'],
            'website_url' => $validated['website_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'logo' => $validated['logo'] ?? $partner->logo,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil diperbarui!');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();

        return back()->with('success', 'Mitra berhasil dihapus!');
    }

    public function toggleStatus(Partner $partner)
    {
        $partner->update(['is_active' => !$partner->is_active]);
        return back()->with('success', 'Status mitra berhasil diubah!');
    }
}
