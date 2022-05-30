@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-9 col-sm m-auto">
        <div class="card">
            <div class="card-header">
                {{ __('Szállítási adatok') }}
            </div>
            <div class="card-body overflow">
                @if (Session::has('err_msg'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('err_msg') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('orders.update.data')}}">
                    @method('PATCH')
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <h1>{{ __('Szállítási és számlázási adatok') }}</h1>
                        </div>
                        <div class="col-6 text-end">
                            <a class="btn btn-primary" href="{{ route('orders.methods') }}">{{ __('Vissza') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Ellenőrzés') }}</button>
                        </div>
                    </div>
                    <div class="row mt-1"> 
                        <div class="col">
                            <h5>Szállítási adatok</h5>
                            <div class="row mt-3"> 
                                <!-- Left side of the form -->
                                <div class="col-6">
                                    <div class="row">
                                        <label for="delivery_first_name" class="form-label">{{ __('Keresztnév*') }}</label>
                                        <div class="col">
                                            <input id="delivery-first-name" type="text" class="form-control @error('delivery_first_name') is-invalid @enderror" name="delivery_first_name" 
                                                @if(old('delivery_first_name')) 
                                                   value="{{ old('delivery_first_name') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->first_name)
                                                    value="{{ $deliveryAddress->first_name }}"
                                                @else
                                                    value="{{ $user->first_name }}"
                                                @endif
                                            >
                                            @error('delivery_first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="delivery_last_name" class="form-label">{{ __('Vezetéknév') }}*</label>
                                        <div class="col">
                                            <input id="delivery-last-name" type="text" class="form-control @error('delivery_last_name') is-invalid @enderror" name="delivery_last_name" 
                                                @if(old('delivery_last_name')) 
                                                    value="{{ old('delivery_last_name') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->last_name)
                                                    value="{{ $deliveryAddress->last_name }}"
                                                @else
                                                    value="{{ $user->last_name }}"
                                                @endif
                                            >
                                            @error('delivery_last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="email" class="form-label">{{ __('Email cím') }}*</label>
                                        <div class="col">
                                            <input id="email" disabled type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?: $user->email }}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="phone_number" class="form-label">{{ __('Telefonszám') }}*</label>
                                        <div class="col">
                                            <input id="phone-number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                                            @if(old('phone_number')) 
                                                    value="{{ old('phone_number') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->phone_number)
                                                    value="{{ $deliveryAddress->phone_number }}"
                                                @else
                                                    value="{{ $user->phone_number }}"
                                                @endif
                                            >
                                            @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Right side of the form -->
                                <div class="col-6">
                                    <div class="row">
                                        <label for="delivery_country" class="form-label">{{ __("Ország") }}*</label>
                                        <div class="col">
                                            <select id="delivery-country" name="delivery_country" class="form-select @error('delivery_country') is-invalid @enderror" @if($personal_delivery_method) disabled @endif>
                                                <option value="">Válassz egy országot</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country['iso'] }}" 
                                                        @if((old('delivery_country') == $country['iso']) || ($deliveryAddress && $deliveryAddress->country == $country['iso'])) 
                                                            selected
                                                        @endif
                                                    >
                                                        {{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('delivery_country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="delivery_city" class="form-label">{{ __('Város') }}*</label>
                                        <div class="col">
                                            <input id="delivery-city" type="text" class="form-control @error('delivery_city') is-invalid @enderror" name="delivery_city" @if($personal_delivery_method) disabled @endif
                                                @if(old('delivery_city')) 
                                                    value="{{ old('delivery_city') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->city)
                                                    value="{{ $deliveryAddress->city }}"
                                                @endif
                                            >
                                            @error('delivery_city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="delivery_street_number" class="form-label">{{ __('Utca és házszám') }}*</label>
                                        <div class="col">
                                            <input id="delivery-street-number" type="text" class="form-control @error('delivery_street_number') is-invalid @enderror" name="delivery_street_number" @if($personal_delivery_method) disabled @endif
                                                @if(old('delivery_street_number')) 
                                                    value="{{ old('delivery_street_number') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->street_number)
                                                    value="{{ $deliveryAddress->street_number }}"
                                                @endif
                                            >
                                            @error('delivery_street_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="delivery_zip_code" class="form-label">{{ __('Irányítószám') }}*</label>
                                        <div class="col">
                                            <input id="delivery-zip-code" type="text" class="form-control @error('delivery_zip_code') is-invalid @enderror" name="delivery_zip_code" @if($personal_delivery_method) disabled @endif
                                            @if(old('delivery_zip_code')) 
                                                    value="{{ old('delivery_zip_code') }}"
                                                @elseif($deliveryAddress && $deliveryAddress->zip_code)
                                                    value="{{ $deliveryAddress->zip_code }}"
                                                @endif
                                            >
                                            @error('delivery_zip_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <input class="form-check-input" type="checkbox" name="different_billing_address" id="different-billing-address" value="1" 
                                @if(old('different_billing_address') || $differentBillingAddress) checked @endif
                            >
                            <label class="form-check-label" for="flexCheckChecked">Szeretném megadni a számlázási adataim</label>
                        </div>
                    </div>
                    <div class="row mt-3 @if( !(old('different_billing_address') || $differentBillingAddress)) d-none @endif" id="billing-part">
                        <div class="col">
                            <h5>Számlázási adatok</h5>
                        </div>
                        <div class="row mt-3"> 
                            <!-- Left side of the form -->
                            <div class="col-6">
                                <div class="row">
                                    <label for="billing_first_name" class="form-label">{{ __('Keresztnév') }}*</label>
                                    <div class="col">
                                        <input id="billing-first-name" type="text" class="form-control @error('billing_first_name') is-invalid @enderror" name="billing_first_name" 
                                        @if(old('billing_first_name')) 
                                                value="{{ old('billing_first_name') }}"
                                            @elseif($billingAddress && $billingAddress->first_name)
                                                value="{{ $billingAddress->first_name }}"
                                            @else
                                                value="{{ $user->first_name }}"
                                            @endif
                                        >
                                        @error('billing_first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="billing_last_name" class="form-label">{{ __('Vezetéknév') }}*</label>
                                    <div class="col">
                                        <input id="billing-last-name" type="text" class="form-control @error('billing_last_name') is-invalid @enderror" name="billing_last_name" 
                                        @if(old('billing_last_name')) 
                                                value="{{ old('billing_last_name') }}"
                                            @elseif($billingAddress && $billingAddress->last_name)
                                                value="{{ $billingAddress->last_name }}"
                                            @else
                                                value="{{ $user->last_name }}"
                                            @endif
                                        >
                                        @error('billing_last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="billing_country" class="form-label">{{ __('Ország') }}*</label>
                                    <div class="col">
                                        <select name='billing_country' class="form-select @error('billing_country') is-invalid @enderror">
                                            <option value="">Válassz egy országot</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country['iso'] }}" 
                                                @if((old('billing_country') == $country['iso']) || ($billingAddress && $billingAddress->country == $country['iso'])) 
                                                    selected
                                                @endif
                                                >
                                                    {{ $country['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('billing_country')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Right side of the form -->
                            <div class="col-6">
                                <div class="row mt-2">
                                    <label for="billing_city" class="form-label">{{ __('Város') }}*</label>
                                    <div class="col">
                                        <input id="billing_city" type="text" class="form-control @error('billing_city') is-invalid @enderror" name="billing_city" 
                                        @if(old('billing_city')) 
                                                value="{{ old('billing_city') }}"
                                            @elseif($billingAddress && $billingAddress->city)
                                                value="{{ $billingAddress->city }}"
                                            @endif
                                        >
                                        @error('billing_city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="billing_street_number" class="form-label">{{ __('Utca és házszám') }}*</label>
                                    <div class="col">
                                        <input id="billing-street-number" type="text" class="form-control @error('billing_street_number') is-invalid @enderror" name="billing_street_number" 
                                        @if(old('billing_street_number')) 
                                                value="{{ old('billing_street_number') }}"
                                            @elseif($billingAddress && $billingAddress->street_number)
                                                value="{{ $billingAddress->street_number }}"
                                            @endif
                                        >
                                        @error('billing_street_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="billing_zip_code" class="form-label">{{ __('Irányítószám') }}*</label>
                                    <div class="col">
                                        <input id="billing-zip-code" type="text" class="form-control @error('billing_zip_code') is-invalid @enderror" name="billing_zip_code" 
                                        @if(old('billing_zip_code')) 
                                                value="{{ old('billing_zip_code') }}"
                                            @elseif($billingAddress && $billingAddress->zip_code)
                                                value="{{ $billingAddress->zip_code }}"
                                            @endif
                                        >
                                        @error('billing_zip_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

const differentBillingAddressCheckBox = document.getElementById('different-billing-address');

differentBillingAddressCheckBox.addEventListener('change', function() {
    const billingPartDiv = document.getElementById('billing-part');
    billingPartDiv.classList.toggle('d-none');
});

</script>
@endsection