<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $calendars = AcademicCalendar::where('is_active', true)
            ->orderByDesc('academic_year')
            ->orderBy('semester')
            ->orderBy('start_date')
            ->get()
            ->groupBy(['academic_year', 'semester']);

        return view('frontend.academic_calendar', compact('calendars'));
    }
}
