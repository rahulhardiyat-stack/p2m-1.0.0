<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()
            ->when($request->search, fn($q) => $q->where('question', 'like', "%{$request->search}%"));

        $faqs = $query->orderBy('order')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        Faq::create([
            ...$validated,
            'is_active' => $request->has('is_active'),
            'order' => $request->input('order', 0),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', __('admin.save_faq') . ' berhasil!');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'integer',
        ]);

        $faq->update([
            ...$validated,
            'is_active' => $request->has('is_active'),
            'order' => $request->input('order', 0),
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', __('admin.save_faq') . ' berhasil diperbarui!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', __('admin.save_faq') . ' berhasil dihapus!');
    }
}
