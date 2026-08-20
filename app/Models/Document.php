<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LocaleFilter;

class Document extends Model
{
    use SoftDeletes, LocaleFilter;

    protected $fillable = [
        'language', 'user_id', 'document_category_id', 'title', 'description', 
        'file_path', 'file_name', 'file_type', 'file_size', 'download_count', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }
}
