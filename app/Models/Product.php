<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The category that belong to the product.
     */
    public function category()
    {
        return $this->belongsToMany(Category::class,'product_category')->withTimestamps();
    }

    /**
     * Get images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }
}
