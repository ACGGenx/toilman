<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    protected $fillable = ['slider_id', 'image_path', 'order', 'is_active', 'url'];


    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}
