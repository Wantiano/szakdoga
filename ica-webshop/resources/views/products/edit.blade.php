@once
    @push('product-scripts')
    <script type="text/javascript" src="/js/product/edit.js"></script>
    @endPush
@endonce

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    {{ __('Termék módosítása') }}
                </div>
                <div class="card-body overflow">
                    <div class="row">
                        <div class="col">
                            <span><strong>{{ __('Termék adatai') }}</strong></span>
                            &nbsp; &nbsp; <span>{{ __('Kötelező mező') }}*</span>
                        </div>
                    </div>
                    <div class="row">
                        <form method="POST" action="{{ route('products.update', $product->id)}}" enctype="multipart/form-data" onsubmit="reorganizeFieldsIndexes()">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <!-- Left side of the form -->
                                <div class="col-md-7">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item row border-0">
                                            <label for="name" class="form-label">{{ __('Termék neve') }}*</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?: $product->name }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <div class="col">
                                                <label for="parent_product_id" class="form-label">{{ __('Készlet') }}*</label>   
                                                &nbsp;
                                                <button class="btn btn-secondary p-1 px-2" id="addStock">{{ __('Hozzáadás') }}</button>
                                            </div>
                                            @if(session('stock-unique'))
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ session('stock-unique') }}</strong>
                                                </span>
                                            @endif
                                            <input type="number" hidden class=" @error('stock_fields_counter') is-invalid @enderror" name="stock_fields_counter" id="stock_fields_counter" value="{{ old('stock_fields_counter') ?: count($stocks) }}">
                                            @error('stock_fields_counter')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">{{ __('Szín') }}</div>
                                                    <div class="col">{{ __('Méret') }}</div>
                                                    <div class="col">{{ __('Darabszám') }}</div>
                                                    <div class="col"></div>
                                                </div>
                                            </div>
                                            <div class="col" id="stock-fields">
                                                @if(is_null(old('stock_fields_counter')))
                                                    @foreach($stocks as $ind => $stock) 
                                                    <div class="row mt-2 stock-field">
                                                        <div class="col">
                                                            <input type="text" name="stock_color_{{ $ind }}" class="stock-fields-input form-control @error('stock_color_' . $ind) is-invalid @enderror" value="{{ old('stock_color_' . $ind) ?: $stock->color }}">
                                                            @error('stock_color_' . $ind)
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" name="stock_size_{{ $ind }}" class="stock-fields-input form-control @error('stock_size_' . $ind) is-invalid @enderror" value="{{ $stock->size }}">
                                                            @error('stock_size_' . $ind)
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" name="stock_in_stock_{{ $ind }}" class="stock-fields-input form-control @error('stock_in_stock_' . $ind) is-invalid @enderror" value="{{ $stock->in_stock }}">
                                                            @error('stock_in_stock_' . $ind)
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col"><button class="btn btn-secondary px-3 removeStock" id="removeStockButton_{{ $ind }}">X</button></div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    @for($ind = 0; $ind < old('stock_fields_counter'); ++$ind)
                                                        <div class="row mt-2 stock-field">
                                                            <div class="col">
                                                                <input type="text" name="stock_color_{{ $ind }}" class="stock-fields-input form-control @error('stock_color_' . $ind) is-invalid @enderror" value="{{ old('stock_color_' . $ind) ?: '' }}">
                                                                @error('stock_color_' . $ind)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col">
                                                                <input type="text" name="stock_size_{{ $ind }}" class="stock-fields-input form-control @error('stock_size_' . $ind) is-invalid @enderror" value="{{  old('stock_size_' . $ind) ?: '' }}">
                                                                @error('stock_size_' . $ind)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col">
                                                                <input type="number" name="stock_in_stock_{{ $ind }}" class="stock-fields-input form-control @error('stock_in_stock_' . $ind) is-invalid @enderror" value="{{ old('stock_in_stock_' . $ind) ?: '' }}">
                                                                @error('stock_in_stock_' . $ind)
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col"><button class="btn btn-secondary px-3 removeStock" id="removeStockButton_{{ $ind }}">X</button></div>
                                                        </div>
                                                    @endfor
                                                @endif
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <label for="description" class="form-label">{{ __('Leírás') }}* <small class="text-muted">{{ __('(max. 1000 karakter)') }}</small></label>
                                            <div class="col">
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') ?: $product->description }}</textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Right side of the form -->
                                <div class="col">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item row border-0">
                                            <div class="row">
                                                <div class="col mb-2">
                                                    {{ __('Ár (HUF)') }}* &nbsp;
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price') ?: $product->price }}">
                                                    @error('price')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <div class="row">
                                                <div class="col mb-2">
                                                    {{ __('Szülőkategória') }}* &nbsp;
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" aria-label="Default select example">
                                                        <option value="0">{{ __('Válassz egy kategóriát') }}</option>
                                                        @foreach($categories as $category)
                                                            <option 
                                                            @if(old('category_id') ? old('category_id') == $category->id : $product->category_id == $category->id)
                                                                selected 
                                                            @endif 
                                                                value="{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <label for="image_fields_counter" class="form-label">
                                                {{ __('Kép feltöltése') }}
                                                <small class="text-muted">(jpg, jpeg, png, bmp, svg, webp)</small>
                                                &nbsp;
                                                <button class="btn btn-secondary p-1 px-2" id="addImage">{{ __('Hozzáadás') }}</button>
                                            </label>
                                            @if(session('no-image'))
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ session('no-image') }}</strong>
                                                </span>
                                            @endif
                                            <input type="number" hidden class="@error('image_fields_counter') is-invalid @enderror" name="image_fields_counter" id="image_fields_counter" value="{{ old('image_fields_counter') ?: 1 }}">
                                            @error('image_fields_counter')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="row" id="image-fields">
                                                @if(!is_null(old('image_fields_counter')))
                                                    @for($ind = 0; $ind < old('image_fields_counter'); ++$ind)
                                                    <div class="image-field row mt-2">
                                                        <div class="col">
                                                            <input type="file" value="{{ old('image_' . $ind) }}" class="form-control image-fields-input @error('image_' . $ind) is-invalid @enderror" name="image_{{ $ind }}">
                                                            @error('image_' . $ind)
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-2"><button class="btn btn-secondary px-3 removeImage" id="removeImageButton_{{ $ind }}">X</button></div>
                                                    </div>
                                                    @endfor
                                                @endif
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <label class="form-label">
                                                {{ __('Termékhez tartozó képek') }}*
                                                <small class="text-muted">(a legelső kép lesz a borítókép)</small>
                                            </label>
                                            <div class="row">
                                                <div class="col px-3">
                                                    <div class="row" id="stored-product-images">
                                                        @foreach($product->images as $image)
                                                            <div class="col-3 px-2 pt-2">
                                                                <a target="_blank" href="{{ $image->imageUrl() }}">
                                                                    <img class="card-img" id="storedProductImage_{{ $loop->index }}" src="{{ $image->imageUrl() }}"/>
                                                                </a>
                                                                <div class="row my-1 px-2">
                                                                    <input type="checkbox" hidden value="1" class="@error('removeStoredProductImageCheckBox_' . $loop->index) is-invalid @enderror" id="removeStoredProductImageCheckBox_{{ $loop->index }}" name="removeStoredProductImageCheckBox_{{ $loop->index }}" {{ old('removeStoredProductImageCheckBox_' . $loop->index) ? 'checked' : '' }}>
                                                                    @error('removeStoredProductImageCheckBox_' . $loop->index)
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <button class="btn btn-secondary removeStoredProductImageCheckButton" id="removeStoredProductImageCheckButton_{{ $loop->index }}">X</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary">{{ __('Módosítás') }}</button>
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">{{ __('Mégsem') }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection