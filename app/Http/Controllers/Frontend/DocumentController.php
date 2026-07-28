<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
class DocumentController extends Controller
{
    /**
     * Display a listing of public documents.
     */
    public function index(Request $request)
    {
        $query = Document::with('category')->where('is_public', true);

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('document_category_id', $request->category);
        }

        $documents = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = DocumentCategory::orderBy('name')->get();

        return view('frontend.documents', compact('documents', 'categories'));
    }
}
