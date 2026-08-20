<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LocaleFilter;

class Gallery extends Model
{
    use LocaleFilter;
    protected $fillable = [
        'language', 'event_id', 'title', 'type', 'file_path', 'thumbnail',
        'youtube_url', 'youtube_id', 'caption', 'album', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
