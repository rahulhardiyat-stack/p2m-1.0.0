<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['menu_id', 'parent_id', 'label', 'url', 'icon', 'target', 'order'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class , 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class , 'parent_id')->orderBy('order');
    }

    public function getResolvedUrlAttribute(): string
    {
        if ($this->route_name && \Illuminate\Support\Facades\Route::has($this->route_name)) {
            try {
                return route($this->route_name);
            }
            catch (\Exception $e) {
            }
        }
        return $this->url ?? '#';
    }
}
