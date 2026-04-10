<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend.faqs', compact('faqs'));
    }
}
