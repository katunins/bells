@extends('layouts.app')
@section('container')

<div class="container">
    <h1>Корзина</h1>

    @if($cart->count() > 0)

    {{-- Корзина --}}
    @else
    {{-- Корзина пустая --}}

    @endif
</div>

@endsection