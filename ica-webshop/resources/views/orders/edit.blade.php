@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ __('Rendelés kezelése') }}
        </div>
        <div class="card-body lead overflow">
            <div class="row">
                <div class="col-8">
                    <h1>{{ __('Rendelés kezelése') }}: <span class="text-muted">{{ $order->public_id }}</span></h1>
                </div>
                <div class="col-4 text-end">
                    <a href="{{ route('orders.allorders') }}" class="btn btn-primary">Vissza</a>
                </div>
            </div>
            @if (Session::has('order-updated'))
                <div id="order-updated" class="alert alert-success" role="alert">
                    {{ __('A rendelés sikeresen frissítve lett!') }}
                </div>
            @endif
            @if($transferPayment)
                <div id="transfer-payment" class="alert alert-info" role="alert">
                    <span>{{ __('A rendelés csak utalás után fogadható el.') }}</span>
                </div>
            @endif
            <div class="row m-3">
                <div class="col-lg-8 col-md-12">
                    @foreach ($products as $product)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <img class="img-fluid" src="{{ $product->product->coverImage() }}" alt="product image"/>
                                </div>
                                <div class="col-10">
                                    <div>
                                        <a class="link-primary text-decoration-none" href="{{ route('products.show', $product->product->id) }}">
                                            {{ $product->product_name }}
                                        </a>
                                        <span>- {{ $product->product_color }} {{ $product->product_size }}</span>
                                    </div>
                                    <div><span class="align-bottom">{{ $product->product_price }} HUF / db</span></div>
                                    <div class="row">
                                        <div class="col">{{ $product->product_price * $product->product_amount }} HUF</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="col">
                        <h3>{{ __('Rendelés állapota') }}</h3>
                        <form method="POST" action="{{ route('orders.update', $order->id)}}">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-auto">
                                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                                        @foreach ($statusArray as $status)
                                        <option value="{{ $status['value'] }}" @if($order->status == $status['value']) selected @endif>
                                            {{ $status['message'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">{{ __('Mentés') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col mt-3">
                        <h3>{{ __('A rendelés összefoglalása') }}</h3>
                        <div class="row mt-3">
                            <div class="col-6">{{ __('Termékek') }}:</div>
                            <div class="col-6">{{ $productsPrice }} {{ __('HUF') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">{{ __('Szállítás') }}:</div>
                            <div class="col-6">
                                @if($deliveryCost > 0)
                                    {{ $deliveryCost }} {{ __('HUF') }}
                                @else
                                    {{ __('ingyenes') }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">{{ __('Fizetés') }}:</div>
                            <div class="col-6">
                                @if($paymentCost > 0)
                                    {{ $paymentCost }} {{ __('HUF') }}
                                @else
                                    {{ __('ingyenes') }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">{{ __('Összesen') }}:</div>
                            <div class="col-6">{{ $summedPrice }} {{ __('HUF') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">{{ __('Állapot') }}:</div>
                            <div class="col-6">{{ $statusMessage }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">{{ __('Rendelés száma') }}:</div>
                            <div class="col-6">{{ $order->public_id }}</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row mt-3">
                            <h3>{{ __('Szállítási adatok') }}</h3>
                            <div>{{ _('Ország') }}: {{ $deliveryCountry }}</div>
                            <div>{{ _('Város') }}: {{ $deliveryAddress->city }}</div>
                            <div>{{ _('Utca és házszám') }}: {{ $deliveryAddress->street_number }}</div>
                            <div>{{ _('Irányítószám') }}: {{ $deliveryAddress->zip_code }}</div>
                            <div>{{ _('Teljes név') }}: {{ $deliveryAddress->last_name . ' ' . $deliveryAddress->first_name }}</div>
                            <div>{{ _('Email cím') }}: {{ $email }}</div>
                            <div>{{ _('Telefonszám') }}: {{ $phone_number }}</div>
                        </div>
                        <div class="row mt-3 @if(!$differentBillingAddress) d-none @endif ">
                            <h3>{{ __('Számlázási adatok') }}</h3>
                            <div>{{ _('Ország') }}: {{ $billingCountry }}</div>
                            <div>{{ _('Város') }}: {{ $billingAddress->city }}</div>
                            <div>{{ _('Utca és házszám') }}: {{ $billingAddress->street_number }}</div>
                            <div>{{ _('Irányítószám') }}: {{ $billingAddress->zip_code }}</div>
                            <div>{{ _('Teljes név') }}: {{ $billingAddress->last_name . ' ' . $billingAddress->first_name }}</div>
                        </div>
                        <div class="row mt-3">
                            <h3>{{ __('Szállítási mód') }}</h3>
                            <div>{{ $deliveryMethod }}</div>
                            <div>
                                @if($deliveryCost > 0)
                                    +{{ $deliveryCost }} {{ __('HUF') }}
                                @else
                                    {{ __('ingyenes') }}
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <h3>{{ __('Fizetési mód') }}</h3>
                            <div>{{ $paymentMethod }}</div>
                            <div>
                                @if($paymentCost > 0)
                                    +{{ $paymentCost }} {{ __('HUF') }}
                                @else
                                    {{ __('ingyenes') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection