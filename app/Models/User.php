<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles, CausesActivity, Impersonate;

    /**
     * @return bool
     */
    public function canImpersonate(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * @return bool
     */
    public function canBeImpersonated(): bool
    {
        // Don't allow impersonating other Super Admins
        return !$this->hasRole('Super Admin');
    }

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3498db&color=fff&size=128';
    }
}
