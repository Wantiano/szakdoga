<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 
        'product_description', 'product_created_by', 
        'product_color', 'product_size', 
        'product_amount', 'product_price',
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productCreator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function coverImage() {
        return $this->product()->coverImage();
    }
}
