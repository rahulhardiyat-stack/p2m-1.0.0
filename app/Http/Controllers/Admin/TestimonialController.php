<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->status === 'approved', fn($q) => $q->where('is_approved', true))
            ->when($request->status === 'pending', fn($q) => $q->where('is_approved', false));

        $testimonials = $query->latest()->paginate(15)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'batch_year' => 'nullable|string|max:4',
            'photo' => 'nullable|image|max:2048',
            'is_approved' => 'boolean',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name = $validated['name'];
        $testimonial->position = $validated['position'];
        $testimonial->company = $validated['company'];
        $testimonial->content = $validated['content'];
        $testimonial->rating = $validated['rating'];
        $testimonial->batch_year = $validated['batch_year'];

        if ($request->hasFile('photo')) {
            $testimonial->photo = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial->is_approved = $request->boolean('is_approved', true);
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'batch_year' => 'nullable|string|max:4',
            'photo' => 'nullable|image|max:2048',
            'is_approved' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $validated['is_approved'] = $request->boolean('is_approved', true);

        $testimonial->update([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'company' => $validated['company'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'batch_year' => $validated['batch_year'],
            'photo' => $validated['photo'] ?? $testimonial->photo,
            'is_approved' => $validated['is_approved'],
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }
        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus!');
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_approved' => !$testimonial->is_approved
        ]);

        return back()->with('success', 'Status testimoni berhasil diubah!');
    }
}
