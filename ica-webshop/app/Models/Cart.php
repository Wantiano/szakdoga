<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'stock_id', 'amount'
    ];

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function getDetailedProductName() {
        return $this->stock->product->name . ' - ' . $this->stock->color . ' ' . $this->stock->size;
    }

    public function getPrice() {
        return $this->stock->product->price * $this->amount;
    }
}
