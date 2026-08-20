<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'language', 'name', 'photo', 'position', 'company', 'content', 'rating', 'batch_year', 'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
