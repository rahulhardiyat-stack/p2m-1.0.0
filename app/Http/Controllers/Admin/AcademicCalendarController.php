<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicCalendar::query()
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('academic_year', 'like', "%{$request->search}%");
            })
            ->when($request->academic_year, fn($q) => $q->where('academic_year', $request->academic_year))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester));

        $calendars = $query->orderByDesc('academic_year')
            ->orderBy('semester')
            ->orderBy('start_date')
            ->paginate(20)
            ->withQueryString();

        $academicYears = AcademicCalendar::select('academic_year')->distinct()->orderByDesc('academic_year')->pluck('academic_year');

        return view('admin.academic_calendars.index', compact('calendars', 'academicYears'));
    }

    public function create()
    {
        return view('admin.academic_calendars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        AcademicCalendar::create($validated);

        return redirect()->route('admin.academic-calendars.index')
            ->with('success', 'Agenda kalender berhasil ditambahkan!');
    }

    public function edit(AcademicCalendar $academicCalendar)
    {
        return view('admin.academic_calendars.edit', compact('academicCalendar'));
    }

    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $academicCalendar->update($validated);

        return redirect()->route('admin.academic-calendars.index')
            ->with('success', 'Agenda kalender berhasil diperbarui!');
    }

    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();
        return back()->with('success', 'Agenda kalender berhasil dihapus!');
    }
}
