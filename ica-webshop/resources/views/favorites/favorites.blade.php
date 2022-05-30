@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Kedvencek') }}</div>
                <div class="card-body overflow">
                    @if (Session::has('favorite-deleted'))
                        <div id="favorite-deleted" class="alert alert-success" role="alert">
                            A(z) <strong id="product-name">{{ Session::get('favorite-deleted') }}</strong> nevű termék sikeresen el lett távolítva a kedvencekből!
                        </div>
                    @endif
                    <div class="row">
                        <h1 class="col-6">{{ __('Kedvencek') }}</h1>
                    </div>
                    @foreach ($favoriteProducts as $product)
                    <div class="card m-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1">
                                    <img class="img-fluid" src="{{ $product->coverImage() }}" alt="product image"/>
                                </div>
                                <div class="col-8">
                                    <div><a class="link-primary text-decoration-none" href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></div>
                                    <div><span>{{ $product->price }} HUF / db</span></div>
                                </div>
                                <div class="col-3 text-end">
                                    <form action="{{ route('favorites.destroy', $product->id) }}" method="POST" style="display: inline">
                                        @method('DELETE')
                                        @csrf
                                        <button id="delete-favorite-btn" type="submit" class="btn bg-transparent p-0 m-0">X</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(count($favoriteProducts) < 1)
                    <div class="container">
                        <div class="col">
                            <h3 class="text-center">Még nincs termék a kedvencekben</h3>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection