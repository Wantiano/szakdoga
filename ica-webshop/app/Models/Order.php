<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id', 'customer_id', 'customer_message', 'status', 
        'order_managed_by', 'order_proccessed_at', 'delivery_data_id',
        'created_at', 'updated_at',
    ];

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function orderManager() {
        return $this->belongsTo(User::class, 'order_managed_by');
    }

    public function deliveryData() {
        return $this->belongsTo(DeliveryData::class, 'delivery_data_id');
    }

    public function products() {
        return $this->hasMany(OrderedProduct::class, 'order_id');
    }

    public function productsPrice() {
        $products = $this->products;
        $price = 0;
        foreach ($products as $product) {
            $price = $price + $product->product_price * $product->product_amount;
        }
        return $price;
    }

    public function sumPrice() {
        return $this->productsPrice() + $this->deliveryData->delivery_cost + $this->deliveryData->payment_cost;
    }

    public function statusMessage() {
        return StatusEnum::getMessage($this->status);
    }

    public function getCreatedDate() {
        return substr($this->created_at, 0, 10);
    }

    public function createOrderedProducts($cart) {
        foreach ($cart as $elem) {
            $product = $elem->stock->product;
            OrderedProduct::create([
                'order_id' => $this->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_color' => $elem->stock->color, 
                'product_size' => $elem->stock->size,
                'product_description' => $product->description,
                'product_amount' => $elem->amount,
                'product_price' => $product->price,
                'product_created_by' => $product->created_by,
            ]);
        }
    }

    public function deliveryOrPaymentMethodIsNotSet() {
        return is_null($this->deliveryData->delivery_method) || is_null($this->deliveryData->payment_method);
    }

    public function deliveryOrBillingAddressIsNotSet() {
        return is_null($this->deliveryData->deliveryAddress) || is_null($this->deliveryData->billingAddress);
    }
}
