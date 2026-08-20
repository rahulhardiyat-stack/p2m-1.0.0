<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'user_id', 'filename', 'original_name', 'path', 'url', 'folder',
        'mime_type', 'extension', 'size', 'width', 'height', 'alt', 'caption',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSizeFormattedAttribute(): string
    {
        if ($this->size < 1024)
            return $this->size . ' B';
        if ($this->size < 1024 * 1024)
            return round($this->size / 1024, 1) . ' KB';
        return round($this->size / (1024 * 1024), 2) . ' MB';
    }
}
