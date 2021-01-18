
<div class="header">
    <div class="logo col-2">
        <a href="/"><img src="images/logo.svg" alt=""></a>
    </div>
    <div class="menu-block col-5">
        <?php 
            $activeRoute = Route::current()->uri;
            $routes = [
                '/'=>'Главная', 
                '/bells'=>'Колокола',
                '/about'=>'Литейная',
                '/contacts'=>'Контакты',
            ];
        ?>

        @foreach ($routes as $href=>$name)
        <a @if ($href==$activeRoute) class="active" @endif href="{{ $href }}">{{ $name }}</a>
        @endforeach

    </div>
    <div class="phone-block col-2">
        <img src="images/phone.svg" alt="">
        <a href="">+7 900 800-22-05</a>
    </div>
    <a class="basket-block col-1" href="/basket">
        <img src="images/basket.svg" alt="">
        <span id="header-basket-summ">{{ number_format(App\Http\Controllers\CartController::getBasketSumm(), 0, '', ' ') }}</span>р
        <button id="basket-arrow">
            <img src="images/basket-arrow.svg" alt="">
        </button>
    </a>
</div>