@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ __('Rendelés ellenőrzése') }}
        </div>
        <div class="card-body overflow">
            <h1>{{ __('Rendelés ellenőrzése') }}</h1>
            <div class="row">
                <!-- Left side of card -->
                <div class="col-6">
                    <div class="row mt-2">
                        <div class="col-11 m-auto">
                            <h4>{{ __('Szállítási adatok') }}</h4>
                            <div>{{ _('Ország') }}: {{ $deliveryCountry }}</div>
                            <div>{{ _('Város') }}: {{ $deliveryAddress->city }}</div>
                            <div>{{ _('Utca és házszám') }}: {{ $deliveryAddress->street_number }}</div>
                            <div>{{ _('Irányítószám') }}: {{ $deliveryAddress->zip_code }}</div>
                            <div>{{ _('Teljes név') }}: {{ $deliveryAddress->last_name . ' ' . $deliveryAddress->first_name }}</div>
                            <div>{{ _('Email cím') }}: {{ $email }}</div>
                            <div>{{ _('Telefonszám') }}: {{ $phone_number }}</div>
                        </div>
                    </div>
                    <div class="row mt-3 @if(!$differentBillingAddress) d-none @endif ">
                        <div class="col-11 m-auto">
                            <h4>{{ __('Számlázási adatok') }}</h4>
                            <div>{{ _('Ország') }}: {{ $billingCountry }}</div>
                            <div>{{ _('Város') }}: {{ $billingAddress->city }}</div>
                            <div>{{ _('Utca és házszám') }}: {{ $billingAddress->street_number }}</div>
                            <div>{{ _('Irányítószám') }}: {{ $billingAddress->zip_code }}</div>
                            <div>{{ _('Teljes név') }}: {{ $billingAddress->last_name . ' ' . $billingAddress->first_name }}</div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-11 m-auto">
                            <h4>{{ __('Szállítási mód') }}</h4>
                            <div>{{ $deliveryMethod }}</div>
                            <div>
                                @if($deliveryCost > 0)
                                    +{{ $deliveryCost }} {{ __('HUF') }}
                                @else
                                    {{ __('ingyenes') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-11 m-auto">
                            <h4>{{ __('Fizetési mód') }}</h4>
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
                <!-- Right side of card -->
                <div class="col-6">
                    <form action="{{ route('orders.store') }}" method="POST" style="display: inline">
                        @csrf
                        <div class="row">
                            <h4>{{ __('Rendelés összefoglalása') }}</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <div>{{ __('Termékek') }}</div>
                                <div>{{ __('Szállítás') }}</div>
                                <div>{{ __('Fizetés') }}</div>
                                <div>{{ __('Összesen') }}</div>
                            </div>
                            <div class="col-6">
                                <div>{{ $productsPrice }} {{ __('HUF') }}</div>
                                <div>{{ $deliveryCost }} {{ __('HUF') }}</div>
                                <div>{{ $paymentCost }} {{ __('HUF') }}</div>
                                <div>{{ $summedPrice }} {{ __('HUF') }}</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <label for="description" class="form-label">{{ __('Megjegyzés:') }} <small class="text-muted">{{ __('(max. 300 karakter)') }}</small></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control @error('customer_message') is-invalid @enderror" id="customer-message" name="customer_message">{{ old('customer_message') ?: '' }}</textarea>
                                        @error('customer_message')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <a href="{{ route('orders.data') }}" class="btn btn-primary">Vissza</a>
                                <button type="submit" class="btn btn-primary">Rendelés leadása</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection