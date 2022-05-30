<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone_number', 'email', 'email_verified_at', 'password', 'is_admin', 'favorites_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart() {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function completedOrders() {
        return $this->orders()->where('status', '!=', StatusEnum::INCOMPLETE);
    }

    public function favoriteProducts() {
        return $this->belongsToMany(Product::class, 'favorites', 'customer_id', 'product_id');
    }

    public function isProductAlreadyFavorite($product) {
        return $this->favoriteProducts->where('id', $product->id)->first() != null;
    }

    public function getIncompleteOrder() {
        $order = $this->orders()->where('status', StatusEnum::INCOMPLETE)->first();
        if(is_null($order)) {
            $deliveryData = DeliveryData::create([
                'phone_number' => $this->phone_number, 
                'email' => User::find($this->id)->email,
                'delivery_method' => null,
                'payment_method' => null,
                'delivery_cost' => null,
                'payment_cost' => null,
                'delivery_address_id' => null,
                'billing_address_id' => null
            ]);

            $order = Order::factory()->create([
                'customer_id' => $this->id,
                'customer_message' => null,
                'status' => StatusEnum::INCOMPLETE,
                'order_managed_by' => null,
                'order_proccessed_at' => null,
                'delivery_data_id' => $deliveryData->id,
                'created_at' => $deliveryData->created_at,
            ]);
        }

        return $order;
    }

    public function cartProductsAmount() {
        $amount = 0;
        foreach($this->cart as $cart) {
            $amount += $cart->amount;
        }
        return $amount;
    }

    public function cartProductsPrice() {
        $price = 0;
        foreach ($this->cart as $cart) {
            $price += $cart->amount * $cart->stock->product->price;
        }
        return $price;
    }

    public function cartIsEmpty() {
        return count($this->cart) == 0;
    }

    public function emptyCart() {
        foreach ($this->cart as $cart) {
            $cart->delete();
        }
    }

    public function subCartFromStocks() {
        foreach ($this->cart as $cart) {
            $stock = $cart->stock;
            $stock->update([
                'in_stock' => $stock->in_stock - $cart->amount,
            ]);
        }
    }

    public function getNotAvailableProductNamesInCart() {
        $notAvailableProductNames = [];

        foreach ($this->cart as $cart) {
            if($cart->stock->in_stock < $cart->amount) {
                $notAvailableProductNames[] = $cart->stock->product->name . ' - ' . $cart->stock->color . ' ' . $cart->stock->size;
            }
        }

        return $notAvailableProductNames;
    }
}