<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_url'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function imageUrl() {
        return asset('storage/images/products/' . $this->image_url);
    }
}
