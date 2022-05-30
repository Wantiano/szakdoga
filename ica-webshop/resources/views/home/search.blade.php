@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">{{ __('Keresési eredmények') }}</div>
                <div class="card-body overflow">
                    <div class="row">
                        <div class="col">
                            <a class="text-decoration-none" href="{{ route('home') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                                    <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h1>{{ __('Keresési eredmények') }}: {{ $search }}</h1>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h4 class="d-inline">{{ __('Kategóriák') }}</h4>
                            <span class="text-secondary">({{ count($categories) }} kategória)</span>
                        </div>
                    </div>
                    @if(count($categories) !== 0)
                    <div class="row">
                        <p>
                            @foreach($categories as $category)
                            <span class="text-secondary"> | </span>
                            <a class="link-secondary text-decoration-none" href="{{ route('categories.show', $category->id) }}">{{ $category->name }}</a>
                            @endforeach
                            <span class="text-secondary"> | </span>
                        </p>
                    </div>
                    @else
                        <div>{{ __('Sajnos nem találtunk kategóriát.') }}</div>
                    @endif

                    <div class="row">
                        <div class="col">
                            <h4 class="d-inline">{{ __('Termékek') }} </h4>
                            <span class="text-secondary">({{ count($products) }} termék)</span>
                        </div>
                    </div>
                    @if(count($products))
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-2 mb-4">
                            <div class="card">
                                <img class="card-img-top" src="{{ $product->coverImage() }}" alt="product cover">
                                <div class="card-body">
                                    <a  href="{{ route('products.show', $product->id) }}" class="card-title h5 stretched-link">{{ $product->name }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                        <div>{{ __('Sajnos nem találtunk terméket.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDeleteWindow() {
    if (confirm("Biztos törölni szeretnéd a kategóriát?")) {
        document.getElementById("delete-category-btn").click();
    }
}
</script>
@endsection