<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicService;
use Illuminate\Http\Request;

class AcademicServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicService::where('is_active', true);

        if ($request->filled('q')) {
            $query->where(fn($q) => $q->where('title', 'like', "%{$request->q}%")->orWhere('description', 'like', "%{$request->q}%"));
        }

        $services = $query->orderBy('order')
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('frontend.academic_service', compact('services'));
    }
}
