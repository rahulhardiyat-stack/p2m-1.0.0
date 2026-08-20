<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'nip', 'nidn', 'photo', 'position', 'academic_title',
        'functional_position', 'expertise', 'education', 'email', 'phone',
        'google_scholar_url', 'sinta_url', 'garuda_url', 'linkedin_url',
        'website_url', 'biography', 'type', 'is_active', 'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3498db&color=fff&size=200';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDosen($query)
    {
        return $query->where('type', 'dosen');
    }

    public function scopeTendik($query)
    {
        return $query->where('type', 'tendik');
    }
}
