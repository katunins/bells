<link rel="stylesheet" href="css/shop.css">

<div class="shop">
    <div class="header-shop-block">
        <input class="shop-search" type="text" name="" id="top-search" placeholder="Подарок на юбилей ..."
            oninput="newSearch()">
    </div>
    <div class="main-shop-block">
        <div class="filter-block col-2">
            @foreach($filter as $filterGroupName => $filterGroup)
                @if(count($filterGroup['data'])>0)
                    <div class="filter-group" theme={{ $filterGroupName }}>
                        <p>{{ $filterGroup['name'] }}</p>
                        @php
                            $quantity = 0;
                        @endphp
                        @foreach($filterGroup['data'] as $key=>$item)
                            @php
                                $quantity++;
                            @endphp
                            <li>
                                <input index={{ $key }} id="{{ $filterGroupName.'_'.$key }}"
                                    type="checkbox" theme={{ $filterGroupName }} value="{{ $item }}"
                                    onchange="changeFilter()" name='checkFilter'>
                                <label @if($quantity> $maxFilterList) class=" hide" needToHide=true @endif
                                    for="{{ $filterGroupName.'_'.$key }}">{{ $item }}</label>
                            </li>
                        @endforeach

                        @if($quantity > $maxFilterList)
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
        <input class="shop-search" type="text" id="bottom-search" placeholder="Подарок на юбилей ..."
            oninput="newSearch()">
    </div>
</div>

<script>
    function changeFilter() {
        window.filter.themes = []
        window.filter.mission = []
        document.querySelectorAll('input[name="checkFilter"]').forEach(el => {
            if (el.checked) {
                window.filter[el.getAttribute('theme')].push (el.value)
            }
        })

        buildScreen(1)
    }

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

    function clearFilter() {
        window.filter = {
            title: '',
            themes: [],
            mission: [],
        }
        buildScreen(1)
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
            price.innerHTML = item.price + ' ₽'

            let weight = document.createElement('div')
            weight.className = 'weight'
            weight.innerHTML = item.weight < 1 ? item.weight * 1000 + ' г' : item.weight +
                ' кг' //item.weight+ ' кг'

            let descriptionBlock = document.createElement('div')
            descriptionBlock.className = 'description-block'

            descriptionBlock.appendChild(titleElem)
            descriptionBlock.appendChild(descriptionElem)
            descriptionBlock.appendChild(price)
            descriptionBlock.appendChild(weight)

            productCardElem.appendChild(backImgElem)
            productCardElem.appendChild(descriptionBlock)

            goodsBlock.appendChild(productCardElem)
        })
    }

    // рендерит кнопки страниц
    //             currentPage:result.currentPage, 
    //             pageQuantity:result.pageQuantity
    function renderPagination(data) {
        if (data.pageQuantity <= 1) return
        let buttonsHtml = ''
        for (let index = 1; index <= data.pageQuantity; index++) {
            buttonsHtml += '<li><button '
            if (index == data.currentPage) buttonsHtml += 'class="active-pagination" '
            buttonsHtml += 'page="' + index + '" '
            buttonsHtml += 'onclick="buildScreen(' + index + ')">'
            buttonsHtml += index
            buttonsHtml += '</button></li>'
        }
        if (data.currentPage < data.pageQuantity) {
            buttonsHtml += '<li><button onclick="buildScreen(0, 1)">Следующая</button></li>'
        }

        let table = document.querySelector('.pagination-block')

        let pagination = document.querySelector('ul.pagination')
        if (pagination === null) {
            pagination = document.createElement('ul')
        }

        pagination.className = 'pagination'
        pagination.setAttribute('maxPage', data.pageQuantity)

        pagination.innerHTML = buttonsHtml
        table.appendChild(pagination)

    }

       // получает экран товаров - page страницу
    // shift - нажата кнопка Следующая
    function buildScreen(page, shift = false) {
        if (shift != false) page = Number(document.querySelector('.active-pagination').getAttribute('page')) + shift
        ajax('getWharehouse', {
            page: page,
            filter: window.filter
        }, function (result) {
            renderPage(result.data)
            renderPagination({
                currentPage: result.currentPage,
                pageQuantity: result.pageQuantity
            })
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

    // ввод в поисковое поле
    function newSearch(reset = false) {
        cloneInputText()
        let titleFilter = event.target.value
        if (reset) titleFilter = ''
        window.filter.title = titleFilter
        buildScreen(1, false)
    }

    document.addEventListener('DOMContentLoaded', function () {
        // прогрузим страницу с продуктом
        clearFilter()

    })

</script>
