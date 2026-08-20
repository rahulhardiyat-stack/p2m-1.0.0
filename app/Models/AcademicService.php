<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicService extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'description',
        'url',
        'is_external',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active' => 'boolean',
    ];
}
