<link rel="stylesheet" href="css/wharehouse.css">
@extends('admin.app')
@section('content')
{{-- {{ dd((true*false*true)==true) }} --}}
<div class="table">
    <div class="table-filter">
        <input type="text" name="filterTitle" id="filterTitle" placeholder="Поиск товара" oninput="newSearch()">
        <button onclick="newSearch(true)">Очистить</button>
    </div>
    <div class="table-head">
        <li class="id-col">id</li>
        <li class="title-col">Название</li>
        <li class="description-col">Описание</li>
        <li class="filterThemeCol">Тематика</li>
        <li class="filterMissionCol">Направление</li>
        <li class="price-col">Цена</li>
        <li class="weight-col">Вес</li>
        <li class="images-col">Изображения</li>
        <li class="quantity-col">Кол-во</li>
        <li class="action-col"></li>
    </div>
    <div class="table-body">
    </div>

</div>
</div>
@endsection

<script>
    // рендерит экран
    // принимает объект из товаров
    function renderPage(data) {
        let tableBody = document.querySelector('.table-body')
        tableBody.innerHTML = ''

        if (data.length > 0) {

            data.forEach(item => {
                let tableItem = document.createElement('div')
                tableItem.className = 'table-item'
                tableItem.setAttribute('productId', item.id)

                let actionButtons = document.createElement('li')
                actionButtons.className = 'action-col'
                actionButtons.innerHTML += '<button onclick="document.location=' + "'newProduct/" + item.id +
                    "'" + '">Изменить</button>'
                actionButtons.innerHTML += '<button onclick="removeProduct(' + item.id +
                    ')" style="color:red;">Удалить</button>'
                actionButtons.innerHTML += '<div class="changeQuantityButtons">' +
                    '<button onclick="changeQuantity(' + item.id + ', -1)">-</button>' +
                    '<button onclick="changeQuantity(' + item.id + ', 1)">+</button>' +
                    '</div>'

                let indexArr = 0 //индекс в массиве изображений для галереи в модальном окне
                let arrayImagePaths = []
                let images = ''
                // let images = '<button class="image-button first-image" style="background-image: url('+item.images.firstImage+')" onclick="openImage({id:'+item.id+', indexArr:'+indexArr+'})"></button>'
                item.images.forEach(image => {
                    arrayImagePaths.push(image)
                    images += '<button class="image-button" style="background-image: url(' + image +
                        ')" onclick="openImage({id:' + item.id + ', indexArr:' + indexArr +
                        '})")></button>'
                    indexArr++
                })
                let arrayImagesInput = document.createElement('input')
                arrayImagesInput.type = 'hidden'
                arrayImagesInput.name = 'imagesOfProduct_' + item.id
                arrayImagesInput.value = JSON.stringify(arrayImagePaths)

                let imagesCol = document.createElement('li')
                imagesCol.className = 'images-col'
                imagesCol.innerHTML = images


                let weightCol = document.createElement('li')
                weightCol.className = 'weight-col'
                weightCol.innerHTML = item.weight < 1 ? item.weight * 1000 + ' г.' : item.weight + ' кг'

                let priceCol = document.createElement('li')
                priceCol.className = 'price-col'
                priceCol.innerHTML = item.price + ' ₽'

                let filterThemeCol = document.createElement('li')
                filterThemeCol.className = 'filterCol'
                let filterThemeColHTML = ''
                item.filter.themes.forEach(el => {
                        filterThemeColHTML += '<li>' + el + '</li>'
                    })
                filterThemeCol.innerHTML = filterThemeColHTML

                let filterMissionCol = document.createElement('li')
                filterMissionCol.className = 'filterCol'
                let filterMissionColHTML = ''
                item.filter.mission.forEach(el => {
                        filterMissionColHTML += '<li>' + el + '</li>'
                    })
                filterMissionCol.innerHTML = filterMissionColHTML

                let descriptionCol = document.createElement('li')
                descriptionCol.className = 'description-col'
                descriptionCol.innerHTML = item.description

                let titleCol = document.createElement('li')
                titleCol.className = 'title-col'
                titleCol.innerHTML = item.title

                let quantityCol = document.createElement('li')
                quantityCol.setAttribute('quantity', item.quantity)
                quantityCol.className = 'quantity-col'
                quantityCol.innerHTML = item.quantity + ' шт.'

                let idCol = document.createElement('li')
                idCol.className = 'id-col'
                idCol.innerHTML = item.id

                tableItem.appendChild(idCol)
                tableItem.appendChild(titleCol)
                tableItem.appendChild(descriptionCol)
                tableItem.appendChild(filterThemeCol)
                tableItem.appendChild(filterMissionCol)
                tableItem.appendChild(priceCol)
                tableItem.appendChild(weightCol)
                tableItem.appendChild(imagesCol)
                tableItem.appendChild(arrayImagesInput)

                tableItem.appendChild(quantityCol)
                tableItem.appendChild(actionButtons)

                if (item.quantity == 0) {
                    tableItem.querySelectorAll('li:not(.action-col)').forEach(el => el.style = 'opacity: 0.3')
                }

                tableBody.appendChild(tableItem)

            });
        }
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

        let table = document.querySelector('.table')

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

    // нажата кнопка поиска
    function newSearch(reset = false) {
        let titleFilter = event.target.value
        if (reset) titleFilter = ''
        window.filter.title = titleFilter
        buildScreen(1, false)
    }

    // нажата картинка
    // id товара
    // index - позиция фотографии в массиве фотографий input name imagesOfProduct_id
    function openImage(data) {
        let imageArrays = document.querySelector('input[name="imagesOfProduct_' + data.id + '"]').value
        turnONmodalGallery({
            imageArrays: JSON.parse(imageArrays),
            productId: data.id,
            indexArr: data.indexArr
        })
        turnONmodal()
    }

    function changeQuantity(id, shift) {
        let productElem = document.querySelector('.table-item[productId="' + id + '"]')
        let countElem = productElem.querySelector('.quantity-col')
        let newQuantity = Number(countElem.getAttribute('quantity')) + Number(shift)
        if (newQuantity >= 0) {
            ajax('changeProductQuantity', {
                id: id,
                newQuantity: newQuantity
            }, function (result) {
                if (result) {
                    countElem.setAttribute('quantity', newQuantity)
                    countElem.innerHTML = newQuantity + ' шт.'
                    if (newQuantity == 0) {
                        productElem.querySelectorAll('li:not(.action-col)').forEach(el => el.style =
                            'opacity: 0.3')
                    } else productElem.querySelectorAll('li:not(.action-col)').forEach(el => el.style =
                        'opacity: 1')
                }
            })
        }

    }

    function removeProduct(id) {

        let productElem = document.querySelector('.table-item[productId="' + id + '"]')
        let title = productElem.querySelector('.title-col').innerHTML
        turnONmodalMessage('Удалить продукт: "' + title + '"?')

        setOkModalButton(function () {
            ajax('removeProduct', {
                id: id
            }, function (result) {
                if (result) {
                    productElem.parentNode.removeChild(productElem);
                    // buildScreen(document.querySelector('.active-pagination').getAttribute('page'))
                    turnOFFSuperModal()
                }
            })
        }, name = 'Удалить')

        setCancelModalButton()
        turnONmodal()
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.filter = {
            title: '',
            themes: [],
            mission: [],
        }
        buildScreen(1)
    })

</script>