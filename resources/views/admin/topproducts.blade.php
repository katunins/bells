<link rel="stylesheet" href="css/topProducts.css">
@extends('admin.app')
@section('content')


<div class="best-cards-container">
    @foreach ($topProduct as $item)
    <div class="best-card">
        <a href="/newTopProduct/{{ $item->id }}">
            <div class="back-bell" style="background-image: url({{ $item->image }})">
                <div class="name-price">
                    <div class="name">{{ $item->name }}</div>
                    <div class="price">{{ $item->pricetext }} р
                        <img src="images/thin-arrow.svg" alt="">
                    </div>
                </div>

                <div class="weight"> @if ($item->weight <1) {{ $item->weight * 1000 }} гр @else {{ $item->weight }} кг
                        @endif </div> </div> </a> <div class="shift-buttons">
                        <form action="shiftTopCard" method="POST">
                            @csrf
                            <input type="hidden" name="id" value={{ $item->id }}>
                            <input type="hidden" name="direction" value="-1">
                            <input type="hidden" name="sort" value={{ $item->sort }}>
                            <button type="submit">←</button>
                        </form>
                        <form action="shiftTopCard" method="POST">
                            @csrf
                            <input type="hidden" name="id" value={{ $item->id }}>
                            <input type="hidden" name="direction" value="0">
                            <input type="hidden" name="sort" value={{ $item->sort }}>
                            <button type="submit">x</button>
                        </form>
                        <form action="shiftTopCard" method="POST">
                            @csrf
                            <input type="hidden" name="id" value={{ $item->id }}>
                            <input type="hidden" name="direction" value="1">
                            <input type="hidden" name="sort" value={{ $item->sort }}>
                            <button type="submit">→</button>
                        </form>
                </div>
            </div>
            @endforeach <div class="best-card">
                <a class="new-top" href="/newTopProduct">+</a> </div>


    </div> @endsection