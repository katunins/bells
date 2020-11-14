<link rel="stylesheet" href="css/welcome.css">
@extends('layouts.app')

@section ('title', 'Русский колокол - литейная мастерская в Воронеже')

@section('container')
<div class="container">

    <div class="block">
        <div class="flex-vert-center col-6">
            <div class="utp-block block-to-center col-4">
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
                <div><img src="images/big-bell.png" alt=""></div>
                <div class="flex-horiz-center">
                    Оригинальный звук
                    <button class="play-sound-button transparent-button">
                        <img src="images/play-sound.svg" alt="">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="block brown-back flex-vert-center">
        <div class="best-bells-block col-6 flex-horiz-center">
            <div class="col-1 flex-horiz-center">
                <button id="best-bells-left" class="transparent-button">
                    <img src="images/arrow-right.svg" alt="">
                </button>
            </div>
            <div class="flex-vert-center">
                <div class="flex-horiz-center">
                    <div class="best-card">
                        <div class="back-bell" style="background-image: url(images/test-bell.jpg)">
                            <div class="name-price">
                                <div class="name">Лики святых</div>
                                <div class="price">5 990 р
                                    <button class="transparent-button">
                                        <img src="images/thin-arrow.svg" alt="">
                                    </button>
                                </div>
                            </div>
                            <div class="weight">1 кг</div>
                        </div>
                    </div>

                    <div class="best-card">
                        <div class="back-bell" style="background-image: url(images/test-bell.jpg)">
                            <div class="name-price">
                                <div class="name">Лики святых</div>
                                <div class="price">5 990 р
                                    <button class="transparent-button">
                                        <img src="images/thin-arrow.svg" alt="">
                                    </button>
                                </div>
                            </div>
                            <div class="weight">1 кг</div>
                        </div>
                    </div>

                    <div class="best-card hide">
                        <div class="back-bell" style="background-image: url(images/test-bell.jpg)">
                            <div class="name-price">
                                <div class="name">Лики святых</div>
                                <div class="price">5 990 р
                                    <button class="transparent-button">
                                        <img src="images/thin-arrow.svg" alt="">
                                    </button>
                                </div>
                            </div>
                            <div class="weight">1 кг</div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="col-3 block-to-center count-line">
                        <hr>
                        <div class="line-elem" style="width: 80px; left: 20px"></div>
                    </div>
                </div>
            </div>
            <div class="col-1 flex-horiz-center">
                <button id="best-bells-right" class="transparent-button">
                    <img src="images/arrow-right.svg" alt="">
                </button>
            </div>
        </div>
        <div class="video-block col-10 block-to-center">

            <div class="text-center color-white">
                <h1>Как правильно выбрать колокол в подарок?</h1>
                <h3 class="line-height-0">Обзор настольных колоколов от Андрея Подорожного</h3>
            </div>
            <img src="images/video-cover.jpg" alt="">
            <div class="arrow-down">
                <img src="images/arrow-down.svg" alt="">
            </div>
            <div class="todo-button block-to-center">Выбрать колокол</div>
        </div>
    </div>

    <div class="block">

    </div>

</div>
@endsection