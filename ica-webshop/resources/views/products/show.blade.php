@extends('layouts.app')

@section('content')
<style>
.custom-control-label {
    background-color: #ffed85;
    border-color: #ffe000;
}
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ $product->name }}
        </div>
        <div class="card-body overflow">
            @if (Session::has('product-updated'))
                <div id="product-updated" class="alert alert-success" role="alert">
                    A(z) <strong id="product-name">{{ Session::get('product-updated') }}</strong> nevű termék sikeresen módosítva lett!
                </div>
            @endif
            @if (Session::has('cart-created'))
                <div id="cart-created" class="alert alert-success" role="alert">
                    A(z) <strong id="cart-created">{{ Session::get('cart-created') }}</strong> nevű termék sikeresen hozzá lett adva a kosárhoz!
                </div>
            @endif
            @if (Session::has('favorite-deleted'))
                <div id="favorite-deleted" class="alert alert-success" role="alert">
                    A(z) <strong id="product-name">{{ Session::get('favorite-deleted') }}</strong> nevű termék sikeresen el lett távolítva a kedvencekből!
                </div>
            @endif
            @if (Session::has('favorite-created'))
                <div id="favorite-created" class="alert alert-success" role="alert">
                    A(z) <strong id="product-name">{{ Session::get('favorite-created') }}</strong> nevű termék sikeresen hozzá lett adva a kedvencekhez!
                </div>
            @endif
            <div class="row">
                <div class="col">
                    <h1>{{ $product->name }}</h1> @if($product->is_deleted) <h4 class="text-danger">A termék törölve lett</h4> @endif
                </div>
                @if($authadmin)
                    <div class="col-3 text-end">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Létrehozás</a>
                        @if(!$product->is_deleted)
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Módosítás</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline">
                                @method('DELETE')
                                @csrf
                                <button id="delete-product-btn" type="submit" hidden @if($product->is_deleted) disabled @endif></button>
                            </form>
                            <button class="btn btn-danger" onclick="confirmDeleteWindow()" @if($product->is_deleted) disabled @endif>Törlés</button>
                        @endif
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col">
                    <a class="text-decoration-none" href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                            <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                        </svg>
                    </a>
                    @isset($parentCategories)
                    @foreach($parentCategories as $category)
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </span>
                    <a class="text-decoration-none align-text-top" href="{{ route('categories.show', $category->id) }}">{{ $category->name }}</a>
                    @endforeach
                    @endisset
                </div>
            </div>
            <div class="row mt-3">
                <!--left side of the card-->
                @if($product->is_deleted)
                    <div class="col">
                @else
                    <div class="col-xl-7 col-sm-auto">
                @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-5">
                                <a href="{{ $product->coverImage() }}">
                                    <img class="card-img" src="{{ $product->coverImage() }}"/>
                                </a>
                                <div class="row">
                                    <div class="col px-3">
                                        <div class="row">
                                            @if(count($product->images) > 1)
                                                @foreach($product->images as $image)
                                                    <div class="col-3 px-2 pt-2">
                                                        <a href="{{ $image->imageUrl() }}">
                                                            <img class="card-img" src="{{ $image->imageUrl() }}"/>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--middle of the form-->
                            <div class="col-7">
                                <div>{{ $product_short_description }}</div>
                                <div class="mt-2"><a href="#product-description">{{ __('Olvass el többet') }}</a></div>
                            </div>
                        </div>
                        <div class="col my-3 mx-1" id="product-description">
                            <h5>Termék részletes leírása</h5>
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
                <!--right side of the card-->
                @if( !$product->is_deleted )
                    <div class="col-xl-5 col-sm-auto">
                        <div class="container">
                            <form method="POST" action="{{ route('cart.store', $product->id)}}">
                                @csrf
                                <div class="row">
                                    <div>Raktáron: {{ $product->inStock() }}db</div>
                                    <h3 class="my-2">{{ $product->price }} HUF</h3>
                                    <div class="my-2 form-group">
                                        <label for="color-size">Szín-méret</label>
                                        <select name='color-size' class="form-select @error('color-size') is-invalid @enderror">
                                            <option value="">Válassz egy szín-méret kombinációt</option>
                                            @foreach ($orderedByColorStocks as $stock)
                                                <option 
                                                    @if(old('color-size') && old('color-size') == $stock->id) selected @endif 
                                                    value="{{ $stock->id }}">{{ $stock->color }} - {{ $stock->size }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('color-size')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    @if(!$authadmin)
                                        <div class="col-3">
                                            <input name="amount" type="number" class="form-control @error('amount') is-invalid @enderror py-2 d-block" 
                                                value="{{ old('amount') && old('amount') > 0 ?: 1 }}">
                                        </div>
                                        <div class="col-auto">
                                            @guest
                                                <a class="btn btn-primary py-2" href="{{ route('login') }}">{{ __('Kosárba') }}</a>
                                            @else
                                                <button type="submit" class="btn btn-primary py-2">Kosárba</button>
                                            @endguest
                                        </div>
                                        <div class="col-auto">
                                            @guest
                                                <a href="{{ route('login') }}" class="btn btn-warning py-2">Kedvencekbe</a>
                                            @else
                                                @if($authuser)
                                                    @if($product_is_in_favorites)
                                                        <a id="from-favorites-button" class="btn btn-warning py-2">Kedvencekből</a>
                                                    @else
                                                        <a id="to-favorites-button" class="btn btn-warning py-2">Kedvencekbe</a>
                                                    @endif
                                                @endif
                                            @endguest
                                        </div>
                                        <div class="row">
                                        @error('amount')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    @endif
                                </div>
                            </form>
                            @if($product_is_in_favorites)
                                <form action="{{ route('favorites.destroy', $product->id) }}" hidden method="POST" style="display: inline">
                                    @method('DELETE')
                                    @csrf
                                    <button id="delete-favorite-btn" type="submit" >delete</button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store', $product->id) }}" hidden method="POST" style="display: inline">
                                    @csrf
                                    <button id="store-favorite-btn" type="submit" >store</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>

function confirmDeleteWindow() {
    if (confirm("Biztos törölni szeretnéd a terméket?")) {
        document.getElementById("delete-product-btn").click();
    }
}
    
init();

function init() {
    const toFavoritesButton = document.getElementById('to-favorites-button');
    if(toFavoritesButton != null) {
        toFavoritesButton.addEventListener('click', submitFavoriteStoreForm);
    }

    const fromFavoritesButton = document.getElementById('from-favorites-button');
    if(fromFavoritesButton != null) {
        fromFavoritesButton.addEventListener('click', submitFavoriteDeleteForm);
    }
}

function submitFavoriteDeleteForm(event) {
    event.preventDefault();
    const button = document.getElementById('delete-favorite-btn');
    if(button != null) {
        button.click();
    }
    console.log(button);
    console.log('delete');
}

function submitFavoriteStoreForm(event) {
    event.preventDefault();
    const button = document.getElementById('store-favorite-btn');
    if(button != null) {
        button.click();
    }
    console.log(button);
    console.log('store');
}

</script>
@endsection