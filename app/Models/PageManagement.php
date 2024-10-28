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
    ];
}
