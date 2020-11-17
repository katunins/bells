<link rel="stylesheet" href="css/shop.css">

<div class="shop">
    <div class="header-shop-block">
        <input class="shop-search" type="text" name="" id="" placeholder="Подарок на юбилей ...">
    </div>
    <div class="main-shop-block">
        <div class="filter-block">
            <div class="filter-group">
                <p>Тематика</p>
                <ul>
                    <li><button class="active-filter-button" filtergroup="theme" value="imena">Имена</button></li>
                    <li><button filtergroup="theme" value="pravoslavnie">Православные</button></li>
                    <li><button filtergroup="theme" value="korobelnie">Коробельные</button></li>
                    <li><button filtergroup="theme" name="openmore">...</button></li>
                </ul>
            </div>
            <div class="filter-group">
                <p>Назначение</p>
                <ul>
                    <li><button filtergroup="mission" value="podarok-na-krestini">Подарок
                            на крестины</button></li>
                    <li><button filtergroup="mission" value="podarok-na-yubiley">Подарок на юбилей</button></li>
                    <li><button filtergroup="mission" value="podarok-rukovoditelu">Подарок руководителю</button></li>
                </ul>
            </div>
            <div class="filter-group">
                <p>Размер</p>
                <ul>
                    <li><button filtergroup="size" value="nastolni-500-gr.">Настольный 500 гр.</button></li>
                    <li><button filtergroup="size" value="Подарок на крестины">Подарок на юбилей</button></li>
                    <li><button filtergroup="size" value="Подарок руководителю">Подарок руководителю</button></li>
                </ul>
            </div>
        </div>
        <div class="goods-block"></div>
    </div>
    <div class="footer-shop-block">
        <ul class="pagination">
            {{-- <li><button direction=-1>Назад</button></li> --}}
            <li><button page=1>1</button></li>
            <li><button page=2 class="active-pagination">2</button></li>
            <li><button page=3>3</button></li>
            <li><button page=4>4</button></li>
            <li><button shift=1>Следующая</button></li>
        </ul>
        <input class="shop-search" type="text" name="" id="" placeholder="Подарок на юбилей ...">
    </div>
</div>