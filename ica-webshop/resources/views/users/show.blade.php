@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('user-updated'))
            <div class="alert alert-success" role="alert">
                A(z) <strong>{{ Session::get('user-updated') }}</strong> sikeresen frissítve lett!
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                Személyes adatok
            </div>
            <div class="card-body overflow">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item row border-0">
                                <form id="user-last-name-form" action="{{ route('users.update') }}" method="POST">
                                    @csrf
                                    <label for="last_name" class="form-label">{{ __('Vezetéknév*') }}</label>
                                    <input id="last-name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') ?: $user['last_name'] }}">
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary mt-2" type="submit">{{ __('Mentés') }}</button>
                                </form>
                            </li>
                            <li class="list-group-item row border-0">
                                <form id="user-first-name-form" action="{{ route('users.update') }}" method="POST">
                                    @csrf
                                    <label for="first_name" class="form-label">
                                        {{ __('Keresztnév*') }}
                                    </label>
                                    <input id="first-name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') ?: $user['first_name'] }}">
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary mt-2" type="submit">{{ __('Mentés') }}</button>
                                </form>
                            </li>
                            <li class="list-group-item row border-0">
                                <form id="user-email-form" action="{{ route('users.update') }}" method="POST">
                                    @csrf
                                    <label for="email" class="form-label">
                                        {{ __('Email cím*') }}
                                    </label>
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?: $user['email'] }}" autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary mt-2" type="submit">{{ __('Mentés') }}</button>
                                </form>
                            </li>
                            <li class="list-group-item row border-0">
                                <form id="user-phone-number-form" action="{{ route('users.update') }}" method="POST">
                                    @csrf
                                    <label for="phone_number" class="form-label">
                                        {{ __('Telefonszám*') }}
                                    </label>
                                    <input id="phone-number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') ?: $user['phone_number'] }}">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary mt-2" type="submit">{{ __('Mentés') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item row border-0">
                                <form id="user-password-form" action="{{ route('users.update') }}" method="POST">
                                    @csrf
                                    <div class="col mb-3">
                                        <label for="old_password" class="form-label">
                                            {{ __('Régi jelszó*') }}
                                        </label>
                                        <input id="old-password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col mb-3">
                                        <label for="new_password" class="form-label">
                                            {{ __('Új jelszó*') }}
                                        </label>
                                        <input id="new-password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password">
                                        @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col mb-3">
                                        <label for="new_password_confirmation" class="form-label">
                                            {{ __('Új jelszó újra*') }}
                                        </label>
                                        <input id="new-password-confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation">
                                        @error('new_password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col mb-3">
                                        <button class="btn btn-primary mt-2" type="submit">{{ __('Jelszó megváltoztatása') }}</button>
                                    </div>
                                </form>
                                <li class="list-group-item row border-0">
                                    <p>*Kötelező mező</p>
                                </li>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection