<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'category_id',
        'meta_title',
        'meta_description',
        'custom_box',
        'status',
        'price',
        'sale_price',
    ];
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function similarProducts()
    {
        return $this->belongsToMany(Product::class, 'product_similar', 'product_id', 'similar_product_id');
    }
}