@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ __('Szállítási és fizetési mód') }}
        </div>
        <div class="card-body overflow">
            @if (Session::has('err_msg'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('err_msg') }}
                </div>
            @endif
            <form method="POST" action="{{ route('orders.update.methods')}}">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-6">
                        <h1>{{ __('Szállítási és fizetési mód') }}</h1>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary" href="{{ route('cart.index') }}">{{ __('Vissza') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('Tovább') }}</button>
                    </div>
                </div>
            
                <h3 class="mt-4">{{ __('Szállítási mód') }}</h3>
                @foreach($delivery_methods as $method)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery-methods" value="{{ $method['value'] }}"
                        @if(old('delivery-methods')) 
                            @if(old('delivery-methods') == $method['value']) checked @endif
                        @else 
                            @if($order->deliveryData->delivery_method == $method['value']) checked @endif
                        @endif
                    >
                    <label class="custom-control-label">
                        {{ $method['message'] . (($method['cost'] > 0) ? (" +" . $method['cost'] . ' HUF') : " ingyenes") }}
                    </label>
                </div>
                @endforeach
                @error('delivery-methods')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                
                <h3 class="mt-4">{{ __('Fizetési mód') }}</h3>
                @foreach($payment_methods as $method)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment-methods" value="{{ $method['value'] }}"
                        @if(old('payment-methods')) 
                            @if(old('payment-methods') == $method['value']) checked @endif
                        @else 
                            @if($order->deliveryData->payment_method == $method['value']) checked @endif
                        @endif
                    >
                    <label class="custom-control-label">
                        {{ $method['message'] . (($method['cost'] > 0) ? (" +" . $method['cost'] . ' HUF') : '') }}
                    </label>
                </div>
                @endforeach
                @error('payment-methods')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </form>
        </div>
    </div>
</div>
@endsection