@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    {{ __('Kategória módosítása') }}
                </div>
                <div class="card-body overflow">
                    @if (Session::has('category-updated'))
                        <div id="category-updated" class="alert alert-success" role="alert">
                            A(z) <strong id="category-name">{{ Session::get('category-updated') }}</strong> nevű kategóriá sikeresen módosítva lett!
                        </div>
                    @endif
                    <div class="row">
                        <form method="POST" action="{{ route('categories.update', $editable_category->id)}}" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <!-- Left side of the form -->
                                <div class="col-md-7">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item row border-0">
                                            <label for="name" class="form-label">{{ __('Kategória neve') }}*</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?: $editable_category->name }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </li>
                                        @if( !$is_base_category )
                                            <li class="list-group-item row border-0">
                                                <label for="parent_category_id" class="form-label">{{ __('Szülőkategória') }}*</label>
                                                <select name="parent_category_id" class="form-select @error('parent_category_id') is-invalid @enderror" aria-label="Default select example">
                                                    <option value="0">Válassz egy kategóriát</option>
                                                @foreach($categories as $category)
                                                    <option 
                                                        @if((old('parent_category_id') && old('parent_category_id') == $category->id) || 
                                                            (!old('parent_category_id') && $editable_category->parent_category_id == $category->id))
                                                            selected
                                                        @endif value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                                </select>
                                                @error('parent_category_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <!-- Right side of the form -->
                                <div class="col">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item row border-0">
                                            <label for="cover_image" class="form-label">
                                                {{ __('Kép feltöltése') }}
                                                <small class="text-muted">(jpg, jpeg, png, bmp, svg, webp)</small>
                                            </label>
                                            <div class="row input-group">
                                                <input type="file" value="{{ old('cover_image') }}" class="form-control @error('cover_image') is-invalid @enderror" name="cover_image">
                                                @error('cover_image')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <label for="cover_image" class="form-label">
                                                {{ __('Jelenlegi borítókép') }}
                                                <small class="text-muted">(új kép feltöltése esetén a régi törlésre kerül)</small>
                                            </label>
                                            <img src='{{ $editable_category->coverImage() }}' alt='category cover'/>
                                        </li>
                                        <li class="list-group-item row border-0">
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary">Módosítás</button>
                                                <a href="{{ route('categories.show', $editable_category->id) }}" class="btn btn-primary">Mégsem</a>
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