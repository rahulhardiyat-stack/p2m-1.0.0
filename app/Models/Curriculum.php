<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use SoftDeletes;

    // Explicitly define the table name because 'curriculum' might cause pluralization issues
    protected $table = 'curriculum';

    protected $fillable = [
        'code', 'name', 'semester', 'credits', 'description',
        'rps_file', 'type', 'concentration', 'is_active', 'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
