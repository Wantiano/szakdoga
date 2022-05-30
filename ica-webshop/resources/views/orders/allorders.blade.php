@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            {{ __('Megrendelések') }}
        </div>
        <div class="card-body overflow">
            <h1>{{ __('Megrendelések') }}</h1>
            <div class="row m-3">
                <div class="col">
                    @forelse ($orders as $order)
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 my-auto">
                                    <div>{{ __('Rendelés száma') }}</div>
                                    <div>{{ $order->public_id }}</div>
                                </div>
                                <div class="col-2 my-auto">
                                    <div>{{ __('Kiállítva') }}</div>
                                    <div>{{ $order->getCreatedDate() }}</div>
                                </div>
                                <div class="col-2 my-auto">
                                    <div>{{ __('Összesen') }}</div>
                                    <div>{{ $order->sumPrice() }} {{ __('HUF') }}</div>
                                </div>
                                <div class="col-3 my-auto">
                                    <div>{{ $order->statusMessage() }}</div>
                                </div>
                                <div class="col-3 my-auto text-end">
                                    <div>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="link-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <h3 class="text-center">Még senki nem adott le rendelést.</h3>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

