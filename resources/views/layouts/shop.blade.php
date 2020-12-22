<link rel="stylesheet" href="css/shop.css">

<div class="shop">
    <div class="header-shop-block">
        <input class="shop-search" type="text" name="" id="top-search" placeholder="Подарок на юбилей ..."
            oninput="newSearch()">
    </div>
    <div class="main-shop-block">
        <div class="filter-block col-2">
            @foreach ($filter as $filterGroupName => $filterGroup)
                @if (count($filterGroup['data']) > 0)
                    <div class="filter-group" theme={{ $filterGroupName }}>
                        <p>{{ $filterGroup['name'] }}</p>
                        @php
                        $quantity = 0;
                        @endphp
                        @foreach ($filterGroup['data'] as $key => $item)
                            @php
                            $quantity++;
                            @endphp
                            <li>
                                <input index={{ $key }} id="{{ $filterGroupName . '_' . $key }}" type="checkbox"
                                    theme={{ $filterGroupName }} value="{{ $item }}" onchange="changeFilter()"
                                    name='checkFilter'>
                                <label @if ($quantity > $maxFilterList) class=" hide"
                                    needToHide=true
                        @endif
                        for="{{ $filterGroupName . '_' . $key }}">{{ $item }}</label>
                        </li>
                @endforeach

                @if ($quantity > $maxFilterList)
                    <li><button status=false theme={{ $filterGroupName }} class="openmore"
                            onclick="openMore()">...</button>
                    </li>
                @endif
        </div>
        @endif
        @endforeach
        <div class="filter-group">
            <button class="clear-filter" onclick="clearFilter()">Сбросить фильтр</button>
        </div>
    </div>

    <div class="goods-block col-8"></div>


</div>
<div class="footer-shop-block">
    <div class="pagination-block">

    </div>
    <input class="shop-search" type="text" id="bottom-search" placeholder="Подарок на юбилей ..." oninput="newSearch()">
</div>
<div class="modal-detail">
    <div class="content">
        <div class="flex-block">
            <div class="detail">
                <div class="block">
                    <h1>Колокол</h1>
                    <p class="description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Unde esse eum
                        inventore
                        saepe sunt. Voluptatum amet, odit praesentium voluptatem blanditiis est in accusantium
                        similique,
                        ullam impedit molestias exercitationem numquam minima?</p>
                </div>
                <div class="block">
                    <h3>Выберете подставку</h3>
                    <div class="stand">
                        @foreach ([
                            [
                                'title' => 'Без подставки',
                                'image' => '',
                            ],
                            [
                                'title' => 'Дуб',
                                'image' => 'images/stand/oak.jpg',
                            ],
                            [
                                'title' => 'Орех',
                                'image' => 'images/stand/oak.jpg',
                            ],
                        ] as $key => $item)
                            <input type="radio" name="desk" id="desk-{{ $key }}" @if ($key == 0) checked
                        @endif >
                        <label for="desk-{{ $key }}" style="background-image: url({{ $item['image'] }})">
                            <span>{{ $item['title'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="image-block">
                <div class="arrows">
                    <button id="detail-bells-left" class="transparent-button" onclick="shiftDetailProducts(-1)">
                        <img style="transform: rotate(180deg);" src="images/arrow-right.svg" alt="">
                    </button>
                    <button id="detail-bells-right" class="transparent-button" onclick="shiftDetailProducts(1)">
                        <img src="images/arrow-right.svg" alt="">
                    </button>
                </div>
                <div class="image" style="background-image: url(images/test-bell.jpg)"></div>
                <div class="gallery">
                    @foreach (['', '', '', '', ''] as $key => $item)
                        <button @if ($key == 0) class="active"
                    @endif style="background-image: url(images/test-bell.jpg)"></button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script src="js/wharehouse.js"></script>

<script>
    function openMore() {
        // console.log(event.target.parentNode.parentNode)
        let eventButton = event.target
        let status = eventButton.getAttribute('status')
        let filterGroupElem = eventButton.parentNode.parentNode
        filterGroupElem.querySelectorAll('label').forEach(
            elem => {
                if (status == 'false') {
                    // раскроем список
                    if (elem.classList.contains('hide')) elem.classList.remove('hide');
                } else {
                    // свернем список
                    if (elem.getAttribute('needtohide') === 'true') {
                        elem.classList.add('hide')
                    }
                }
            })
        eventButton.setAttribute('status', status === 'false')
    }

    // перерисовывает продукты на странице
    function renderPage(data) {
        let goodsBlock = document.querySelector('.goods-block')
        goodsBlock.innerHTML = ''

        data.forEach(item => {
            let productCardElem = document.createElement('div')
            productCardElem.className = 'product-card col-2'

            let backImgElem = document.createElement('div')
            backImgElem.className = 'back-img'
            backImgElem.style.backgroundImage = 'url(' + item.images[0] + ')'

            let titleElem = document.createElement('div')
            titleElem.className = 'title'
            titleElem.innerHTML = item.title

            let descriptionElem = document.createElement('div')
            descriptionElem.className = 'description'
            descriptionElem.innerHTML = item.description

            let price = document.createElement('div')
            price.className = 'price'
            price.innerHTML = item.quantity > 0 ? item.price + ' ₽' : 'Нет на складе'

            let weight = document.createElement('div')
            weight.className = 'weight'
            weight.innerHTML = item.weight < 1 ? item.weight * 1000 + ' г' : item.weight +
                ' кг' //item.weight+ ' кг'

            let descriptionBlock = document.createElement('div')
            descriptionBlock.className = 'description-block'

            let toOpen = document.createElement('span')
            toOpen.className = 'arrow-to-open'
            toOpen.innerHTML = '→'

            descriptionBlock.appendChild(titleElem)
            descriptionBlock.appendChild(descriptionElem)
            descriptionBlock.appendChild(price)

            if (item.quantity > 0) descriptionBlock.appendChild(toOpen);
            descriptionBlock.appendChild(weight)

            productCardElem.appendChild(backImgElem)
            productCardElem.appendChild(descriptionBlock)



            // if (item.quantity == 0) productCardElem.classList.add ('none')

            goodsBlock.appendChild(productCardElem)
        })
    }

    // Клонирует value у поисковых инпутов - дублеров
    function cloneInputText() {
        let inputElems = document.querySelectorAll('.shop-search')
        let currentValue = event.target.value

        inputElems.forEach(el => {
            if (el.value != currentValue) el.value = currentValue
        })
    }

</script>
