<link rel="stylesheet" href="css/shop.css">

<div id="shop">
    <div class="header-shop-block">
        <input class="shop-search" type="text" name="" id="top-search" placeholder="Подарок на юбилей ..."
            oninput="newSearch()">
    </div>
    <div class="main-shop-block">
        <div class="filter-block col-2">
            <?php $__currentLoopData = $filter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filterGroupName => $filterGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(count($filterGroup['data']) > 0): ?>
            <div class="filter-group" theme=<?php echo e($filterGroupName); ?>>
                <p><?php echo e($filterGroup['name']); ?></p>
                <?php
                $quantity = 0;
                ?>
                <?php $__currentLoopData = $filterGroup['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $quantity++;
                ?>
                <li>
                    <input index=<?php echo e($key); ?> id="<?php echo e($filterGroupName . '_' . $key); ?>" type="checkbox"
                        theme=<?php echo e($filterGroupName); ?> value="<?php echo e($item); ?>" onchange="changeFilter()" name='checkFilter'>
                    <label <?php if($quantity> $maxFilterList): ?> class=" hide"
                        needToHide=true
                        <?php endif; ?>
                        for="<?php echo e($filterGroupName . '_' . $key); ?>"><?php echo e($item); ?></label>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($quantity > $maxFilterList): ?>
                <li><button status=false theme=<?php echo e($filterGroupName); ?> class="openmore" onclick="openMore()">...</button>
                </li>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <button id="detail-bells-left" class="transparent-button" onclick="shiftGallery(-1)">
                            <img style="transform: rotate(180deg);" src="images/arrow-right.svg" alt="">
                        </button>
                        <button id="detail-bells-right" class="transparent-button" onclick="shiftGallery(1)">
                            <img src="images/arrow-right.svg" alt="">
                        </button>
                    </div>
                    <div id="image"></div>
                    <div id="detail-gallery">
                    </div>
                </div>

                <div class="detail">

                    <input type="hidden" name="product-price" value="">
                    <div class="block">
                        <h1 id="detail-title"></h1>

                        <p id="detail-weight" class="weight"></p>
                        <p id="detail-description"></p>
                    </div>
                    <div id="detail-tags">
                    </div>
                    <div class="block">
                        <h3>Выберите подставку</h3>
                        <div class="stand">
                            <?php $__currentLoopData = [
                            [
                            'title' => 'Без подставки',
                            'price' => 0,
                            'image' => '',
                            ],
                            [
                            'title' => 'Дуб',
                            'price' => 1300,
                            'image' => 'images/stand/oak.jpg',
                            ],
                            [
                            'title' => 'Орех',
                            'price' => 1300,
                            'image' => 'images/stand/oak.jpg',
                            ],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="stand-group">
                                <input type="radio" name="stand" price="<?php echo e($item['price']); ?>" id="stand-<?php echo e($key); ?>"
                                    <?php if($key==0): ?> checked <?php endif; ?> onchange="changeStand('<?php echo e($item['title']); ?>')">
                                <label for="stand-<?php echo e($key); ?>" style="background-image: url(<?php echo e($item['image']); ?>)">
                                </label>
                                <p><?php echo e($item['title']); ?></p>
                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <form action="addToCart" method="POST" class="block to-order">
                        <?php echo csrf_field(); ?>
                        <div id="to-order-price"></div>
                        <input type="hidden" name="stand" value="Без подставки" id="order-stand">
                        <input type="hidden" name="orderSumm" value="">
                        <input type="hidden" name="productId" value="">
                        <input type="submit" value="Заказать">
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

    function shiftGallery(shift){
        let imageElem = document.getElementById('image')
        let activeImage=document.querySelector('.gallery-button.active')
        let currentNum = Number(activeImage.getAttribute('num'))
        let galleryLength = Number(activeImage.getAttribute('length'))
        currentNum +=shift
        if (currentNum >=0 && currentNum<galleryLength) {
            activeImage.classList.remove('active')
            activeImage = document.querySelector('.gallery-button[num="'+currentNum+'"]')
            activeImage.classList.add('active')
            document.getElementById('image').style.backgroundImage = activeImage.style.backgroundImage
        }
    }

    function pressGallery(num){
        let activeNum = Number(document.querySelector('.gallery-button.active').getAttribute('num'))
        shiftGallery (num - activeNum)
    }

    function changeStand(deskName) {
        document.getElementById('order-stand').value=deskName
        summUpdate()
    }

    function summUpdate() {
        let destSumm = Number(document.querySelector('input[name="stand"]:checked').getAttribute('price'))
        let productSumm = Number(document.querySelector('input[name="product-price"]').value)
         document.getElementById('to-order-price').innerHTML= productSumm+ destSumm
         document.querySelector('input[name="orderSumm"]').value = productSumm+ destSumm
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
            document.querySelector('input[name="product-price"]').value=data.price
            let imagesHTML=''
            for (key in data.images) {
                imagesHTML+='<button class="gallery-button'+(key == 0 ? ' active' : '')+'" style="background-image: url('+data.images[key]+')" num='+key+' length='+data.images.length+' onclick="pressGallery('+key+')"></button>'
            }
            document.getElementById('detail-gallery').innerHTML=imagesHTML
            document.getElementById('image').style.backgroundImage='url('+data.images[0]+')'
            document.querySelector('input[name="productId"]').value = data.id
            summUpdate()
            // Render 

            detailElem.classList.remove('disable')
        } else detailElem.classList.add('disable');
    }

</script><?php /**PATH /Users/pavelkatunin/Documents/bells.ikatunin.ru/resources/views/layouts/shop.blade.php ENDPATH**/ ?>