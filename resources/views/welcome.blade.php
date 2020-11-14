<link rel="stylesheet" href="css/welcome.css">
@extends('layouts.app')

@section ('title', 'Русский колокол - литейная мастерская в Воронеже')

@section('container')
<div class="container">
    <div class="block grey-back">
        <div class="flex-vert-center col-6">
            <div class="block-to-center col-4">
                <h1>Подарочный настольный колокол с быстрой доставкой</h1>
                <p>Литейная мастерская колоколов из натуральной бронзы доставит оригинальный подарок с вашим именем или
                    ликом святого. Найди свой колокол от 5 990 руб!</p>
                <a href="">
                    <div class="todo-button">Выбрать колокол</div>
                </a>
            </div>
        </div>
        <div class="col-6 flex-vert-center">
            <div class="col-5 block-to-center">
                <img src="images/big-bell.png" alt="">
            </div>
        </div>
    </div>
</div>
@endsection