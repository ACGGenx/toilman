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
        return $this->hasMany(ProductImage::class)->orderBy('is_primary', 'desc');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')
                    ->withTimestamps();
    }

    public function similarProducts()
    {
        return $this->belongsToMany(Product::class, 'product_similar', 'product_id', 'similar_product_id');
    }

    public function getDiscountPercentageAttribute()
{
    if ($this->sale_price && $this->price > 0) {
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }
    return 0;
}

public function getCurrentPriceAttribute()
{
    return $this->sale_price && $this->sale_price < $this->price 
        ? $this->sale_price 
        : $this->price;
}

public function hasDiscount()
{
    return $this->sale_price && $this->sale_price < $this->price;
}
    
}
