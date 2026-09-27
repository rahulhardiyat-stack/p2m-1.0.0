<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relasi ke model Accreditation (Satu Fakultas memiliki banyak data akreditasi prodi)
     */
    public function accreditations()
    {
        return $this->hasMany(Accreditation::class);
    }
}
