<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'color', 'size', 'in_stock'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function carts() {
        return $this->hasMany(Cart::class, 'stock_id');
    }

    public function getCartWhereUser($user) {
        return $this->carts()->where('customer_id', $user->id)->first();
    }

    /**
     * Determine if stock is in relevants.
     * 
     * @param Collection|array $relevantStocks
     * @return boolean
     */
    public function isRelevant($relevantStocks) {
        foreach($relevantStocks as $stock) {
            if($this->product_id == $stock->product_id && $this->color == $stock->color && $this->size == $stock->size) {
                return true;
            }
        }
        return false;
    }
}
