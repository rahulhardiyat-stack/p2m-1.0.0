<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Infographic;
use Illuminate\Http\Request;

class InfographicController extends Controller
{
    public function index()
    {
        $infographics = Infographic::latest('title')->paginate(10);
        return view('admin.infographics.index', compact('infographics'));
    }

    public function create()
    {
        return view('admin.infographics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:infographics,title',
            'stats_labels.*' => 'required|string|max:255',
            'stats_values.*' => 'required|integer|min:0',
        ]);

        $stats = [];
        if ($request->has('stats_labels')) {
            foreach ($request->stats_labels as $index => $label) {
                $stats[] = [
                    'label' => $label,
                    'value' => $request->stats_values[$index] ?? 0
                ];
            }
        }

        if ($request->boolean('is_active')) {
            Infographic::query()->update(['is_active' => false]);
        }

        Infographic::create([
            'title' => $request->title,
            'stats' => $stats,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.infographics.index')->with('success', 'Info Grafis berhasil ditambahkan.');
    }

    public function edit(Infographic $infographic)
    {
        return view('admin.infographics.edit', compact('infographic'));
    }

    public function update(Request $request, Infographic $infographic)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:infographics,title,' . $infographic->id,
            'stats_labels.*' => 'required|string|max:255',
            'stats_values.*' => 'required|integer|min:0',
        ]);

        $stats = [];
        if ($request->has('stats_labels')) {
            foreach ($request->stats_labels as $index => $label) {
                $stats[] = [
                    'label' => $label,
                    'value' => $request->stats_values[$index] ?? 0
                ];
            }
        }

        if ($request->boolean('is_active')) {
            Infographic::query()->update(['is_active' => false]);
        }

        $infographic->update([
            'title' => $request->title,
            'stats' => $stats,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.infographics.index')->with('success', 'Info Grafis berhasil diperbarui.');
    }

    public function destroy(Infographic $infographic)
    {
        $infographic->delete();
        return redirect()->route('admin.infographics.index')->with('success', 'Info Grafis berhasil dihapus.');
    }

    public function activate(Infographic $infographic)
    {
        Infographic::query()->update(['is_active' => false]);
        $infographic->update(['is_active' => true]);

        return redirect()->route('admin.infographics.index')->with('success', 'Data Info Grafis Aktif berhasil diubah.');
    }
}
