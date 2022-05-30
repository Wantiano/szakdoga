@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @if (Session::has('category-deleted'))
            <div id="category-deleted" class="alert alert-success" role="alert">
                A(z) <strong id="category-name">{{ Session::get('category-deleted') }}</strong> kategória sikeresen törölve lett!
            </div>
            @endif
            @if (Session::has('category-updated'))
            <div id="category-updated" class="alert alert-success" role="alert">
                {{ __('A kategória sikeresen frissítve lett!') }}
            </div>
            @endif
            @if (Session::has('product-deleted'))
            <div id="category-updated" class="alert alert-success" role="alert">
                A(z) <strong>{{ Session::get('product-deleted') }}</strong> nevű termék sikeresen törölve lett!
            </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Főoldal') }}</div>
                <div class="card-body overflow">
                    <h1>{{ $categoryName }}</h1>
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
                        <div class="col-6">
                            <h2>{{ __('Kategóriák') }}</h2>
                        </div>
                        @if($authadmin)
                            <div class="col text-end">
                                <a href="{{ route('categories.create') }}" class="btn btn-primary">Létrehozás</a>
                                @isset($category_id)
                                <a href="{{ route('categories.edit', $category_id) }}" class="btn btn-primary">Módosítás</a>
                                @if(!$is_base_category)
                                    <form action="{{ route('categories.destroy', $category_id) }}" method="POST" style="display: inline">
                                        @method('DELETE')
                                        @csrf
                                        <button id="delete-category-btn" type="submit" hidden></button>
                                    </form>
                                    <button class="btn btn-danger" onclick="confirmDeleteWindow()">Törlés</button>
                                    @endif
                                @endisset
                            </div>
                        @endif
                    </div>
                    @if(count($categories) !== 0)
                    <div class="row">
                        @foreach($categories as $category)
                        <div class="col-md-2 mb-4">
                            <div class="card">
                                <img class="card-img-top" src="{{ $category->coverImage() }}" alt="category cover"/>
                                <div class="card-body">
                                    <a href="{{ route('categories.show', $category->id) }}" class="card-title h5 stretched-link">{{ $category->name }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-6">
                            <h2>{{ __('Termékek') }}</h2>
                        </div>
                        @if($authadmin)
                            <div class="col text-end">
                                <a href="{{ route('products.create') }}" class="btn btn-primary">{{ __('Létrehozás') }}</a>
                            </div>
                        @endif
                    </div>
                    @isset($products)
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-2 mb-4">
                            <div class="card">
                                <img class="card-img-top" src="{{ $product->coverImage() }}" alt="product cover">
                                <div class="card-body">
                                <a  
                                    @isset($category_id)
                                        href="{{ route('products.show', $product->id, $category_id) }}"
                                    @else
                                        href="{{ route('products.show', $product->id) }}"
                                    @endisset
                                    class="card-title h5 stretched-link">{{ $product->name }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endisset
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