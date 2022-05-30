<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Stock;
use App\Utils\ValidatorUtil;
use Illuminate\Http\Request;
use Auth;

class CartController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the cart connected to user
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $data = [
            'carts' => $user->cart, 
            'priceSum' => $user->cartProductsPrice(),
            'productCount' => $user->cartProductsAmount(),
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('cart.cart', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id - product's id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $product = Product::find($id);
        if(is_null($product) || $product->is_deleted) abort(404);

        $validated = $request->validate(
            ValidatorUtil::getStoreValidationRulesForCart($product),
            ValidatorUtil::getStoreValidationMessagesForCart()
        );

        $stock = Stock::find($validated['color-size']);
        $cart = $stock->getCartWhereUser($user);

        if(!is_null($cart)) {
            $cart->update(['amount' => $validated['amount']]);
        } else {
            Cart::create([
                'stock_id' => $stock->id,
                'customer_id' => $user->id,
                'amount' => $validated['amount'],
            ]);
        }

        $request->session()->flash('cart-created', $product->name);
        $data = [
            'product' => $product,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return redirect()->route('products.show',  $data);
    }

    /**
     * Update the specified resource in storage. If cart's amount is zero, delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $cart = Cart::find($id);
        if(is_null($cart)) abort(404);

        if( $cart->customer->id != $user->id) abort(404);

        $fieldName = 'product-stock-amount-' . $id;
        $validated = $request->validate(
            ValidatorUtil::getUpdateValidationRulesForCart($fieldName),
            ValidatorUtil::getUpdateValidationMessagesForCart($fieldName)
        );

        $backRedirectResponse = back();

        if($validated[$fieldName] > 0) {
            $cart->update(['amount' => $validated[$fieldName]]);
        } else {
            $backRedirectResponse->with('cart-deleted', $cart->getDetailedProductName());
            $cart->delete();
        }

        return $backRedirectResponse;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $cart = Cart::find($id);
        if(is_null($cart)) abort(404);

        if( $cart->customer->id != $user->id ) abort(404);

        $cart->delete();

        return redirect()->route('cart.index')->with('cart-deleted', $cart->getDetailedProductName());
    }

    /**
     * Increase cart's amount by one.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function increase($id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $cart = Cart::find($id);
        if(is_null($cart)) abort(404);

        if($user->id != $cart->customer->id) abort(404);

        $cart->amount = $cart->amount + 1;
        $cart->save();  

        return back();
    }

    /**
     * Decrease cart's amount by one. If amount reaches zero, delete.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decrease($id)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $cart = Cart::find($id);
        if(is_null($cart)) abort(404);

        if($user->id != $cart->customer->id) abort(404);

        $backRedirectResponse = back();

        if($cart->amount > 1) {
            $cart->update(['amount' => $cart->amount - 1]);
        } else {
            $backRedirectResponse->with('cart-deleted', $cart->getDetailedProductName());
            $cart->delete();
        }

        return $backRedirectResponse;
    }
}