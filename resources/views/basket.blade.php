@extends('layouts.app')
@section('container')

<div class="container">

    @if ($cart->count() > 0)
    {{-- Корзина --}}
    <h1>Корзина</h1>
    @else
    {{-- Корзина пустая --}}
    <h1>Корзина пустая</h1>
    <a href="/">Выбрать колокол</a>
    @endif
</div>
@endsection