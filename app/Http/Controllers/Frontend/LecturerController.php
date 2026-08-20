<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::active()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('order');

        $dosen = Lecturer::active()->dosen()->orderBy('order')->get();
        $tendik = Lecturer::active()->where('type', 'tendik')->orderBy('order')->get();

        return view('frontend.lecturers.index', compact('dosen', 'tendik'));
    }

    public function show(Lecturer $lecturer)
    {
        abort_unless($lecturer->is_active, 404);
        return view('frontend.lecturers.show', compact('lecturer'));
    }
}
