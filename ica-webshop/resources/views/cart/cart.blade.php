@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Kosár') }}</div>
                <div class="card-body overflow">
                    @if (Session::has('cart-deleted'))
                    <div id="cart-deleted" class="alert alert-success" role="alert">
                        A(z) <strong id="product-name">{{ Session::get('cart-deleted') }}</strong> termék sikeresen el lett távolítva a kosárból!
                    </div>
                    @endif
                    @if (Session::has('notAvailableProductNames'))
                        @foreach (Session::get('notAvailableProductNames') as $key => $notAvaliableProductName)
                        <div id="not-available-product-{{ $key }}" class="alert alert-danger" role="alert">
                            A(z) <strong id="product-name">{{ $notAvaliableProductName }}</strong> termékből nem érhető el a kért mennyiség!
                        </div>
                        @endforeach
                    @endif
                    @if (Session::has('deletedProductNames'))
                        @foreach (Session::get('deletedProductNames') as $key => $deletedProductName)
                        <div id="deleted-product-{{ $key }}" class="alert alert-danger" role="alert">
                            A(z) <strong id="product-name">{{ $deletedProductName }}</strong> termék törölve lett!
                        </div>
                        @endforeach
                    @endif
                    @if (Session::has('empty_cart'))
                        <div id="empty_cart" class="alert alert-danger" role="alert">
                            A kosár egy terméket sem tartalmaz!
                        </div>
                    @endif
                    <div class="row">
                        <h1 class="col-6">{{ __('Kosár') }}</h1>
                        @if($productCount > 0)
                            <div class="col-6 text-end"><a class="btn btn-primary" href="{{ route('orders.methods') }}">{{ __('Rendelés') }}</a></div>
                        @endif
                    </div>
                    @foreach ($carts as $cart)
                    <div class="card m-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1">
                                    <img class="img-fluid" src="{{ $cart->stock->product->coverImage() }}" alt="product image"/>
                                </div>
                                <div class="col-8">
                                    <div>
                                        <a class="link-primary text-decoration-none" href="{{ route('products.show', $cart->stock->product->id) }}">
                                            {{ $cart->stock->product->name }}
                                        </a>
                                        <span>- {{ $cart->stock->color }} {{ $cart->stock->size }}</span>
                                    </div>
                                    <div><span class="align-bottom">{{ $cart->stock->product->price }} HUF / db</span></div>
                                    <div class="row">
                                        <div class="col-auto">{{ $cart->stock->product->price * $cart->amount }} HUF</div>
                                        <div class="col-auto">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <form action="{{ route('cart.decrease', $cart->id) }}" method="POST" style="display: inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary px-3">-</button>
                                                    </form>
                                                </div>
                                                <div class="col-3">
                                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" style="display: inline">
                                                        @csrf
                                                        <input name="product-stock-amount-{{ $cart->id }}" id="product-stock-amount-{{ $cart->id }}" class="form-control input-sm product-stock-amount-input" type="number" value="{{ $cart->amount }}" onfocusout="pressSubmitButton({{ $cart->id }});">
                                                        <button type="submit" hidden id="product-stock-amount-button-{{ $cart->id }}"></button>
                                                    </form>
                                                </div>
                                                <div class="col-auto">
                                                    <form action="{{ route('cart.increase', $cart->id) }}" method="POST" style="display: inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary px-3">+</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" style="display: inline">
                                        @method('DELETE')
                                        @csrf
                                        <button id="delete-cart-btn" type="submit" class="btn bg-transparent p-0 m-0">X</button>
                                    </form>
                                    <div class="d-flex align-items-end flex-column">
                                        <div class="position-absolute bottom-0">
                                        {{ $cart->stock->in_stock }} {{ __('db készleten') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="container">
                        <div class="col">
                            @if($productCount > 0)
                            <h3>{{ __('Kosár összefoglalása') }}</h3>
                            <div class="row">
                                <div class="col-auto">
                                    <div>Termék</div>
                                    <div>Összesen</div>
                                </div>
                                <div class="col-auto text-end">
                                    <div>{{ $productCount }} db</div>
                                    <div>{{ $priceSum }} HUF</div>
                                </div>
                            </div>
                            @else
                            <h3 class="text-center">Még nincs termék a kosárban</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>

function pressSubmitButton(id) {
    document.getElementById('product-stock-amount-button-' + id).click();
}

</script>
</div>
@endsection