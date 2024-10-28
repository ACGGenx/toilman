<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    protected $fillable = ['slider_id', 'image_path', 'order'];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}
