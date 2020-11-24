<link rel="stylesheet" href="css/shop.css">

<div class="shop">
    <div class="header-shop-block">
        <input class="shop-search" type="text" name="" id="top-search" placeholder="Подарок на юбилей ...">
    </div>
    <div class="main-shop-block">
        <div class="filter-block col-2">

            @foreach ($shop->filterState as $item)
            <div class="filter-group" theme={{ $item['theme'] }}>
                <p>{{ $item['theme'] }}</p>
                <ul>
                    <?php $quantity = 0;?>
                    @foreach ($item['filters'] as $filter)
                    <?php $quantity++;?>
                    <li>

                        <input id="{{ $filter['id'] }}" type="checkbox" @if ($filter['checked']) checked @endif
                            theme={{ $item['theme'] }} value={{ $filter['name'] }}
                            onchange="changeFilter({{ $filter['id'] }})">
                        <label @if($quantity> 3) class=" hide" needToHide=true @endif
                            for="{{ $filter['id'] }}">{{ $filter['name'] }}</label>
                    </li>
                    @endforeach
                    @if ($quantity > 3)
                    <li><button status=false theme={{ $item['theme'] }} class="openmore"
                            onclick="openMore('{{ $item['theme'] }}')">...</button></li>
                    @endif
                </ul>
            </div>
            @endforeach

            {{-- <div class="filter-group">
                <p>Цена</p>
                <span>
                    от <input type="text" name="min" value="{{ $shop->filterPrice['min'] }}">
            </span>
            <span>
                до <input type="text" name="max" value="{{ $shop->filterPrice['max'] }}">
            </span>
        </div> --}}



        <div class="filter-group">
            <button class="clear-filter" onclick="clearFilter()">Сбросить фильтр</button>
        </div>
    </div>
    <div class="goods-block col-8">
        {{-- @foreach ($shop->productsPage as $item)
        <div class="product-card col-2">
            <div class="back-img" style="background-image: url({{ $item['image'] }})"></div>
    <div class="description-block">
        <div class="title">{{ $item['title'] }}</div>
        <div class="description">{{ $item['description'] }}</div>
        <div class="price">{{ $item['price'] }} ₽</div>
        <div class="weight">{{ $item['weight'] }} кг.</div>
    </div>
</div>
@endforeach --}}

</div>
</div>
<div class="footer-shop-block">
    <ul class="pagination" maxPage={{ count($shop->pageState) }}>
        @if (count($shop->pageState) > 1)
        @for ($i = 0; $i < count($shop->pageState); $i++) <li>
                <button @if ($shop->pageState[$i]['active']) class="active-pagination" @php ($currentPage=$i+1)
                    @endif
                    page={{ $shop->pageState[$i]['num'] }} onclick =
                    "pageChange({num:{{ $shop->pageState[$i]['num'] }}, shift:
                    false})">{{ $shop->pageState[$i]['num'] }}
                </button>

            </li>
            @endfor
            <li><button class="@if($currentPage==count($shop->pageState)) hide @endif" shift=1
                    onclick="pageChange({num: null, shift: +1})">Следующая</button>
            </li>
            @endif
    </ul>
    <input class="shop-search" type="text" id="bottom-search" placeholder="Подарок на юбилей ...">
</div>
</div>

<script>
    function pageChange (data) {
        let activePageElem = document.querySelector('.active-pagination')
        let currentPage = parseInt (activePageElem.getAttribute('page'))
        let isLastPage = currentPage == parseInt (document.querySelector('.pagination').getAttribute('maxPage'))
        let newPage = false

        if (data.shift) {
            if (!isLastPage) newPage = currentPage + data.shift
        } else {
            newPage = data.num
        }

        if (newPage) {
            activePageElem.classList.remove ('active-pagination')
            document.querySelector('button[page="'+newPage+'"]').classList.add('active-pagination')
            ajax ('changePage', {num:newPage}, data => renderPage(data))
        }

    }

    function changeFilter (id) {
        ajax('changeFilter', {id:id}, data => renderPage(data))
    }

    function openMore(theme) {
        document.querySelector('.filter-group[theme="'+theme+'"]').querySelectorAll('label[needtohide]').forEach(elem=>{
            if (elem.classList.contains('hide')) elem.classList.remove('hide'); else elem.classList.add('hide');
        })
    }

    function clearFilter() {
        ajax ('clearFilter', {}, data => renderPage(data))
    }

    // перерисовывает продукты на странице
    function renderPage(data) {
        let goodsBlock = document.querySelector('.goods-block')
        goodsBlock.innerHTML = ''
        
        data.forEach (item=>{    
            let productCardElem = document.createElement('div')
            productCardElem.className = 'product-card col-2'

            let backImgElem = document.createElement('div')
            backImgElem.className='back-img'
            backImgElem.style.backgroundImage = 'url('+item.image+')'

            let titleElem = document.createElement('div')
            titleElem.className = 'title'
            titleElem.innerHTML = item.title

            let descriptionElem = document.createElement('div')
            descriptionElem.className = 'description'
            descriptionElem.innerHTML = item.description

            let price = document.createElement('div')
            price.className = 'price'
            price.innerHTML = item.price+' ₽'

            let weight = document.createElement('div')
            weight.className = 'weight'
            weight.innerHTML = item.weight+ ' кг'

            let descriptionBlock = document.createElement('div')
            descriptionBlock.className = 'description-block'

            descriptionBlock.appendChild (titleElem)
            descriptionBlock.appendChild (descriptionElem)
            descriptionBlock.appendChild (price)
            descriptionBlock.appendChild (weight)

            productCardElem.appendChild(backImgElem)
            productCardElem.appendChild (descriptionBlock)
            
            goodsBlock.appendChild(productCardElem)


            
            // goodsBlock.innerHTML += '<div class="product-card col-2">'
                // goodsBlock.innerHTML += '<div class="back-img" style="background-image: url('+item.image+')"></div>'
                // goodsBlock.innerHTML += '<div class="description-block">'
                //     goodsBlock.innerHTML += '<div class="title">'+item.title+'</div>'
                //     goodsBlock.innerHTML += '<div class="description">'+item.description+'</div>'
                //     goodsBlock.innerHTML += '<div class="price">'+item.price+' ₽</div>'
                //     goodsBlock.innerHTML += '<div class="weight">'+item.weight+' кг.</div>'
                // goodsBlock.innerHTML +='</div>'
            // goodsBlock.innerHTML +='</div>'
        })
    }

    document.addEventListener ('DOMContentLoaded', function () {
        // прогрузим страницу с продуктом
        ajax ('getProductToPage', {}, (data)=>{
            renderPage (data)
        })
    })
</script>