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
                        theme={{ $filterGroupName }} value="{{ $item }}" onchange="changeFilter()" name='checkFilter'>
                    <label @if ($quantity> $maxFilterList) class=" hide"
                        needToHide=true
                        @endif
                        for="{{ $filterGroupName . '_' . $key }}">{{ $item }}</label>
                </li>
                @endforeach

                @if ($quantity > $maxFilterList)
                <li><button status=false theme={{ $filterGroupName }} class="openmore" onclick="openMore()">...</button>
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
        <input class="shop-search" type="text" id="bottom-search" placeholder="Подарок на юбилей ..."
            oninput="newSearch()">
    </div>
    <div class="modal-detail disable">

        <div class="content">

            <button class="close" onclick="turnDetail()"></button>
            <div class="flex-block">

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
                    <div id="detail-gallery">
                        {{-- @foreach (['', '', '', '', ''] as $key => $item)
                        <button @if ($key==0) class="active" @endif
                            style="background-image: url(images/test-bell.jpg)"></button>
                        @endforeach --}}
                    </div>
                </div>

                <div class="detail">
                    <div class="block">
                        <h1 id="detail-title"></h1>
                        <p id="detail-description"></p>
                    </div>
                    <p id="detail-weight" class="weight"></p>
                    <div id="detail-tags">
                    </div>
                    <div class="block">
                        <h3>Выберите подставку</h3>
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
                            ]
                            as $key => $item)
                            <div class="desk-group">
                                <input type="radio" name="desk" id="desk-{{ $key }}" @if ($key==0) checked @endif>
                                <label for="desk-{{ $key }}" style="background-image: url({{ $item['image'] }})">
                                    {{-- <span>{{ $item['title'] }}</span>
                                    --}}
                                </label>
                                <p>{{ $item['title'] }}</p>
                            </div>

                            @endforeach
                        </div>
                    </div>
                    <form class="block to-order">
                        <div id="to-order-price"></div>
                        <button>Заказать</button>
                    </form>
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

            let inputData = document.createElement('input')
            inputData.type = 'hidden'
            inputData.className = 'input-data'
            inputData.value = JSON.stringify(item)

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
            productCardElem.appendChild(inputData)

            // if (item.quantity == 0) productCardElem.classList.add ('none')

            goodsBlock.appendChild(productCardElem)
            productCardElem.onclick = turnDetail

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

    function turnDetail() {

        let detailElem = document.querySelector('.modal-detail')
        if (detailElem.classList.contains('disable')) {
            // created_at: "2020-12-17T08:41:44.000000Z"
            // description: "Этот колокол я взял для теста. Тестовый колокол лучше нигде не применять, так как он не настоящий и сделан только ради того, что бы подстроить алгоритмы и верстку"
            // filter: {themes: Array(2), mission: Array(2)}
            // id: 1
            // images: (4) ["/storage/productImages/1/4ixcf5wF7hBvPQBzrUpxoNNPpXAvQu9diHAJ8k7o.jpeg", "/storage/productImages/1/dww99pb1FmDPLgfwZbeKsz0JhdDpvxBlgp1ytzwl.jpeg", "/storage/productImages/1/P1z8C6ypZ0ksoxm0kDwrl0AWmMCsLEHsJd41h1tg.jpeg", "/storage/productImages/1/AkLuoHfoRmPhHHJRhkLaSjyrO5j83ZAq3YPQHfxv.jpeg"]
            // price: 5990
            // quantity: 0
            // title: "Колокол"
            // updated_at: "2020-12-21T10:09:01.000000Z"
            // weight: 0.3
            let data = JSON.parse(event.currentTarget.querySelector('.input-data').value)
            document.getElementById('detail-title').innerHTML = data.title
            document.getElementById('detail-description').innerHTML = data.description
            document.getElementById('detail-weight').innerHTML = data.weight < 1 ? data.weight * 1000 + ' г' : data
                .weight + ' кг'
            let tagsHTML = ''
            data.filter.themes.forEach(item => {
                tagsHTML += '<li>' + item + '</li>'
            })
            data.filter.mission.forEach(item => {
                tagsHTML += '<li>' + item + '</li>'
            })
            document.getElementById('detail-tags').innerHTML = tagsHTML
            document.getElementById('to-order-price').innerHTML=data.price
            let imagesHTML=''
            data.images.forEach(item=>{
                imagesHTML+='<button style="background-image: url('+item+')"></button>'
            })
            document.getElementById('detail-gallery').innerHTML=imagesHTML
            detailElem.classList.remove('disable')
        } else detailElem.classList.add('disable');
    }

</script>