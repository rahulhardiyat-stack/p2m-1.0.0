<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->firstOrFail();

        if (!$page->is_published && !auth()->check()) {
            abort(404);
        }

        $builderCss = '';
        if ($page->is_builder && $page->builder_data) {
            $data = json_decode($page->builder_data, true);
            $builderCss = $data['css'] ?? '';
        }

        $documentCategory = DocumentCategory::where('slug', $page->slug)->first();
        $documents = null;

        if ($documentCategory) {
            $documents = Document::with('category')
                ->where('is_public', true)
                ->where('document_category_id', $documentCategory->id)
                ->latest()
                ->paginate(15)
                ->withQueryString();
        }

        // Process dynamic blocks in content
        $content = $page->content;
        $content = preg_replace_callback('/<div[^>]*class="[^"]*dynamic-block[^"]*"[^>]*data-type="([^"]+)"[^>]*>.*?<\/div>|<div[^>]*data-type="([^"]+)"[^>]*class="[^"]*dynamic-block[^"]*"[^>]*>.*?<\/div>/is', function ($matches) {
            $type = !empty($matches[1]) ? $matches[1] : $matches[2];
            if (view()->exists("frontend.dynamic_blocks.{$type}")) {
                return view("frontend.dynamic_blocks.{$type}")->render();
            }
            return '<!-- Dynamic block type not found: ' . $type . ' -->';
        }, $content);
        //test halaman statik
        return view('frontend.pages.show-test', [
            'page' => $page,
            'builderCss' => $builderCss,
            'processedContent' => $content,
            'documents' => $documents,
            'documentCategory' => $documentCategory,
        ]);
    }
}
