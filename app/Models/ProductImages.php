<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
	protected $fillable = ['product_id','image_name'];
	
	protected $table = 'product_images';
    
   /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}