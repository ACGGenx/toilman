<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'detail',
        'product_id',
        'type',
    ];

    // Define relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
