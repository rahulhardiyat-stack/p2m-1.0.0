<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'is_read', 'replied_at', 'reply'];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
