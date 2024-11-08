<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageManagement extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'seo_tags',
        'url',
        'status',
        'is_default'
    ];

    protected static function boot()
    {
        parent::boot();

        // When saving a page with is_default = true, set all other pages to false
        static::saving(function ($page) {
            if ($page->is_default) {
                static::where('id', '!=', $page->id)->update(['is_default' => false]);
            }
        });
    }
}
