<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Enums\CountryEnum;
use App\Enums\DeliveryMethodEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\StatusEnum;
use App\Utils\ShopAddress;
use App\Utils\ValidatorUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
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
     * Show the form for order submitting.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        if($user->cartIsEmpty()) {
            return redirect()->route('cart.index')->with('empty_cart', true);
        }
        
        $notAvailableProductNames = $user->getNotAvailableProductNamesInCart();
        
        if(count($notAvailableProductNames) > 0) {
            return redirect()->route('cart.index')->with('notAvailableProductNames', $notAvailableProductNames);
        }

        $order = $user->getIncompleteOrder();

        if($order->deliveryOrPaymentMethodIsNotSet()) {
            return redirect()->route('orders.methods')->with('err_msg', 'Válassz szállítási és fizetési módot');
        }

        if($order->deliveryOrBillingAddressIsNotSet()) {
            return redirect()->route('orders.data')->with('err_msg', 'Add meg a szállítási és számlázási adatokat.');
        }

        $productsPrice = $user->cartProductsPrice();
        $deliveryCost = DeliveryMethodEnum::getCost($order->deliveryData->delivery_method);
        $paymentCost = PaymentMethodEnum::getCost($order->deliveryData->payment_method);

        $data = [
            'phone_number' => $order->deliveryData->phone_number,
            'email' => $order->deliveryData->email,
            'deliveryAddress' => $order->deliveryData->deliveryAddress,
            'billingAddress' => $order->deliveryData->billingAddress,
            'deliveryCountry' => CountryEnum::getName($order->deliveryData->deliveryAddress->country),
            'billingCountry' => CountryEnum::getName($order->deliveryData->billingAddress->country),
            'deliveryMethod' => DeliveryMethodEnum::getMessage($order->deliveryData->delivery_method),
            'deliveryCost' => $deliveryCost,
            'paymentMethod' => PaymentMethodEnum::getMessage($order->deliveryData->payment_method),
            'paymentCost' => $paymentCost,
            'differentBillingAddress' => !$order->deliveryData->addressesEqual(),
            'productsPrice' => $productsPrice,
            'summedPrice' => $productsPrice + $deliveryCost + $paymentCost,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders.check', $data);
    }

    /**
     * Submit order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        if($user->cartIsEmpty()) {
            return redirect()->route('cart.index')->with('empty_cart', true);
        }
        
        $notAvailableProductNames = $user->getNotAvailableProductNamesInCart();
        
        if(count($notAvailableProductNames) > 0) {
            return redirect()->route('cart.index')->with('notAvailableProductNames', $notAvailableProductNames);
        }

        $order = $user->getIncompleteOrder();

        if($order->deliveryOrPaymentMethodIsNotSet()) {
            return redirect()->route('orders.methods')->with('err_msg', 'Válassz szállítási és fizetési módot');
        }

        if($order->deliveryOrBillingAddressIsNotSet()) {
            return redirect()->route('orders.data')->with('err_msg', 'Add meg a szállítási és számlázási adatokat.');
        }

        $validated = $request->validate(
            ValidatorUtil::getStoreValidationRulesForOrder(), 
            ValidatorUtil::getStoreValidationMessagesForOrder()
        );

        $order->createOrderedProducts($user->cart);
        $user->subCartFromStocks();
        $user->emptyCart();

        $now = Carbon::now();
        $order->update([
            'customer_message' => $validated['customer_message'],
            'status' => StatusEnum::PENDING,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return redirect()->route('orders.show', $order->id);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::find($id);
        if(is_null($order)) abort(404);

        // Not the owner
        if($order->customer_id != $user->id) abort(404);

        $productsPrice = $order->productsPrice();
        $deliveryCost = $order->deliveryData->delivery_cost;
        $paymentCost = $order->deliveryData->payment_cost;

        $data = [
            'order' => $order,
            'phone_number' => $order->deliveryData->phone_number,
            'email' => $order->deliveryData->email,
            'deliveryAddress' => $order->deliveryData->deliveryAddress,
            'billingAddress' => $order->deliveryData->billingAddress,
            'deliveryCountry' => CountryEnum::getName($order->deliveryData->deliveryAddress->country),
            'billingCountry' => CountryEnum::getName($order->deliveryData->billingAddress->country),
            'deliveryMethod' => DeliveryMethodEnum::getMessage($order->deliveryData->delivery_method),
            'deliveryCost' => $deliveryCost,
            'paymentMethod' => PaymentMethodEnum::getMessage($order->deliveryData->payment_method),
            'paymentCost' => $paymentCost,
            'differentBillingAddress' => !$order->deliveryData->addressesEqual(),
            'productsPrice' => $productsPrice,
            'summedPrice' => $productsPrice + $deliveryCost + $paymentCost,
            'products' => $order->products,
            'transferPayment' => $order->deliveryData->payment_method == PaymentMethodEnum::PAYPAL,
            'statusMessage' => StatusEnum::getMessage($order->status),
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders/show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * Manage order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $order = Order::find($id);
        if(is_null($order) || $order->status == StatusEnum::INCOMPLETE) abort(404);

        $productsPrice = $order->productsPrice();
        $deliveryCost = $order->deliveryData->delivery_cost;
        $paymentCost = $order->deliveryData->payment_cost;
        $statusArray = StatusEnum::statusArray();
        unset($statusArray[0]);

        $data = [
            'order' => $order,
            'phone_number' => $order->deliveryData->phone_number,
            'email' => $order->deliveryData->email,
            'deliveryAddress' => $order->deliveryData->deliveryAddress,
            'billingAddress' => $order->deliveryData->billingAddress,
            'deliveryCountry' => CountryEnum::getName($order->deliveryData->deliveryAddress->country),
            'billingCountry' => CountryEnum::getName($order->deliveryData->billingAddress->country),
            'deliveryMethod' => DeliveryMethodEnum::getMessage($order->deliveryData->delivery_method),
            'deliveryCost' => $deliveryCost,
            'paymentMethod' => PaymentMethodEnum::getMessage($order->deliveryData->payment_method),
            'paymentCost' => $paymentCost,
            'differentBillingAddress' => !$order->deliveryData->addressesEqual(),
            'productsPrice' => $productsPrice,
            'summedPrice' => $productsPrice + $deliveryCost + $paymentCost,
            'products' => $order->products,
            'transferPayment' => $order->deliveryData->payment_method == PaymentMethodEnum::PAYPAL,
            'statusMessage' => StatusEnum::getMessage($order->status),
            'statusArray' => $statusArray,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort(404);

        $order = Order::find($id);
        if( is_null($order) || $order->status == StatusEnum::INCOMPLETE ) abort(404);

        $validated = $request->validate(
            ValidatorUtil::getUpdateValidationRulesForOrder(), 
            ValidatorUtil::getUpdateValidationMessagesForOrder()
        );

        $order->update(['status' => $validated['status']]);

        $request->session()->flash('order-updated', true);
        return redirect()->route('orders.edit', $id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myorders()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $data = [
            'orders' => $user->completedOrders,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders.myorders', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allorders()
    {
        $user = Auth::user();
        if( !$user->is_admin ) abort (404);

        $allOrdersDescendingCreatedAt = Order::where('status', '!=', StatusEnum::INCOMPLETE)->orderBy('created_at', 'desc')->get();
        $data = [
            'orders' => $allOrdersDescendingCreatedAt,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders.allorders', $data);;
    }

    /**
     * Show the form for delivery and paymed method.
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
    */
    public function methods()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);
        
        if($user->cartIsEmpty()) {
            return redirect()->route('cart.index')->with('empty_cart', true);
        }
        
        $notAvailableProductNames = $user->getNotAvailableProductNamesInCart();
        if(count($notAvailableProductNames) > 0) {
            return redirect()->route('cart.index')->with('notAvailableProductNames', $notAvailableProductNames);
        }

        $data = [
            'order' => $user->getIncompleteOrder(),
            'delivery_methods' => DeliveryMethodEnum::methodArray(), 
            'payment_methods' => PaymentMethodEnum::methodArray(),
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];

        return view('orders.methods', $data);
    }

    /**
     * Update methods in delivery data in order.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
    */
    public function updateMethods(Request $request)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);
        
        if($user->cartIsEmpty()) {
            return redirect()->route('cart.index')->with('empty_cart', true);
        }
        
        $notAvailableProductNames = $user->getNotAvailableProductNamesInCart();
        
        if(count($notAvailableProductNames) > 0) {
            return redirect()->route('cart.index')->with('notAvailableProductNames', $notAvailableProductNames);
        }

        $validated = $request->validate(
            ValidatorUtil::getUpdateMethodsValidationRulesForOrder(),
            ValidatorUtil::getUpdateMethodsValidationMessagesForOrder()
        );

        $order = $user->getIncompleteOrder();

        $deliveryData = $order->deliveryData;
        
        if($validated['delivery-methods'] == DeliveryMethodEnum::PERSONAL) {
            $deliveryData->setDeliveryAddress([
                'delivery_first_name' => $user->first_name,
                'delivery_last_name' => $user->last_name,
                'delivery_country' => ShopAddress::COUNTRY,
                'delivery_city' => ShopAddress::CITY,
                'delivery_street_number' => ShopAddress::STREET_NUMBER,
                'delivery_zip_code' => ShopAddress::ZIP_CODE
            ]);  
        }

        $deliveryData->update([
            'delivery_method' => $validated['delivery-methods'],
            'payment_method' => $validated['payment-methods'],
            'delivery_cost' => DeliveryMethodEnum::getCost($validated['delivery-methods']),
            'payment_cost' => PaymentMethodEnum::getCost($validated['payment-methods'])
        ]);

        return redirect()->route('orders.data');
    }

    /**
     * Display data form.
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function data()
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $order = $user->getIncompleteOrder();

        if($order->deliveryOrPaymentMethodIsNotSet()) {
            return redirect()->route('orders.methods')->with('err_msg', 'Válassz szállítási és fizetési módot');
        }

        $data = [
            'user' => $user,
            'countries' => CountryEnum::countryArray(),
            'order' => $order,
            'differentBillingAddress' => !$order->deliveryData->addressesEqual(),
            'personal_delivery_method' => $order->deliveryData->delivery_method == DeliveryMethodEnum::PERSONAL,
            'deliveryAddress' => $order->deliveryData->deliveryAddress,
            'billingAddress' => $order->deliveryData->billingAddress,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('orders.data', $data);
    }

    /**
     * Update delivery data.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateData(Request $request)
    {
        $user = Auth::user();
        if($user->is_admin) abort(404);

        $order = $user->getIncompleteOrder();

        if($order->deliveryOrPaymentMethodIsNotSet()) {
            return redirect()->route('orders.methods')->with('err_msg', 'Válassz szállítási és fizetési módot');
        }

        $differentBillingAddress = $request->has('different_billing_address');

        $deliveryMethodIsPersonal = $order->deliveryData->delivery_method == DeliveryMethodEnum::PERSONAL;

        $validator = [
            ValidatorUtil::getUpdateDataValidationRules($differentBillingAddress, $deliveryMethodIsPersonal), 
            ValidatorUtil::getUpdateDataValidationMessages()
        ];

        $validated = $request->validate($validator[0], $validator[1]);
        
        if($deliveryMethodIsPersonal) {
            $validated['delivery_country'] = ShopAddress::COUNTRY;
            $validated['delivery_city'] = ShopAddress::CITY;
            $validated['delivery_street_number'] = ShopAddress::STREET_NUMBER;
            $validated['delivery_zip_code'] = ShopAddress::ZIP_CODE;
        }

        $deliveryData = $order->deliveryData;
        $deliveryData->email = $user->email;
        $deliveryData->phone_number = $validated['phone_number'];
        $deliveryData->setDeliveryAddress($validated);
        $deliveryData->setBillingAddress($differentBillingAddress, $validated);
        $deliveryData->save();

        return redirect()->route('orders.check');
    }
}
