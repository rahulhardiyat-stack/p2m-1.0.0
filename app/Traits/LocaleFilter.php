<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LocaleFilter
{
    /**
     * Scope a query to only include items for the current language.
     */
    public function scopeForLocale(Builder $query, ?string $locale = null)
    {
        return $query->where('language', $locale ?: app()->getLocale());
    }
}
