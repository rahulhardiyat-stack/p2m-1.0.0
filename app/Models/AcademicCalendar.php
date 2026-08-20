<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    protected $fillable = [
        'title',
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'type',
        'description',
        'color',
        'is_active',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
