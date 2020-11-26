<link rel="stylesheet" href="css/wharehouse.css">
@extends('admin.app')
@section('content')
<div class="table">
    <div class="table-filter">
        {{-- <input type="text" name="maxprice" id="maxprice" value="0">
        <input type="text" name="minprice" id="minprice" value="10000000"> --}}
        <input type="text" name="filterTitle" id="filterTitle" placeholder="Поиск товара" oninput="newSearch()">
        {{-- <button onclick="newSearch()">Найти</button> --}}
        <button onclick="newSearch(true)">Очистить</button>
    </div>
    <div class="table-head">
        <li class="id-col">id</li>
        {{-- <li class="code-col">Код</li> --}}
        <li class="title-col">Название</li>
        <li class="description-col">Описание</li>
        <li class="price-col">Цена</li>
        <li class="weight-col">Вес</li>
        <li class="images-col">Изображения</li>
    </div>
    <div class="table-body">
    </div>


</div>
</div>
@endsection

<script>
    // рендерит экран
    // принимает объект из товаров
    function renderPage (data) {
        let tableBody = document.querySelector('.table-body')
            tableBody.innerHTML =''
        if (data.length > 0) {
            
            data.forEach(item => {
                let tableItem = document.createElement('div')
                tableItem.className = 'table-item'
                let indexArr = 0 //индекс в массиве изображений для галереи в модальном окне
                let arrayImagePaths = [item.images.firstImage]
                let images = '<button class="image-button first-image" style="background-image: url('+item.images.firstImage+')" onclick="openImage({id:'+item.id+', imagePath:`'+item.images.firstImage+'`, index:'+indexArr+'})"></button>' //{id:'+item.id', imagePath:'+item.images.firstImage+'}
                item.images.imagesPaths.forEach (image=>{
                    indexArr ++
                    arrayImagePaths.push(image)
                    images += '<button class="image-button" style="background-image: url('+image+')" onclick="openImage({id:'+item.id+', imagePath:`'+image+'`, index:'+indexArr+'})")></button>'
                })
                let arrayImagesInput = document.createElement ('input')
                arrayImagesInput.type = 'hidden'
                arrayImagesInput.name = 'imagesOfProduct_'+item.id
                arrayImagesInput.value = JSON.stringify (arrayImagePaths)
                
                let imagesCol = document.createElement('li')
                imagesCol.className = 'images-col'
                imagesCol.innerHTML= images


                let weightCol = document.createElement('li')
                weightCol.className = 'weight-col'
                weightCol.innerHTML = item.weight+' кг'

                let priceCol = document.createElement('li')
                priceCol.className = 'price-col'
                priceCol.innerHTML = item.price+' ₽'

                let descriptionCol = document.createElement('li')
                descriptionCol.className = 'description-col'
                descriptionCol.innerHTML = item.description

                let titleCol = document.createElement('li')
                titleCol.className = 'title-col'
                titleCol.innerHTML = item.title
                
                let idCol = document.createElement('li')
                idCol.className = 'id-col'
                idCol.innerHTML = item.id

                tableItem.appendChild(idCol)
                tableItem.appendChild(titleCol)
                tableItem.appendChild(descriptionCol)
                tableItem.appendChild(priceCol)
                tableItem.appendChild(weightCol)
                tableItem.appendChild(imagesCol)
                tableItem.appendChild(arrayImagesInput)

                tableBody.appendChild(tableItem)
                
            });
        } 
    }

    // рендерит кнопки страниц
    //             currentPage:result.currentPage, 
    //             pageQuantity:result.pageQuantity
    function renderPagination (data) {
        if (data.pageQuantity <=1 )return
        let buttonsHtml =''
        for (let index = 1; index <= data.pageQuantity; index++) {
            buttonsHtml +='<li><button '
            if (index == data.currentPage) buttonsHtml += 'class="active-pagination" '
            buttonsHtml +='page="'+index+'" '
            buttonsHtml +='onclick="buildScreen('+index+')">'
            buttonsHtml +=index
            buttonsHtml +='</button></li>'
        }
        if (data.currentPage < data.pageQuantity) {
            buttonsHtml += '<li><button onclick="buildScreen(0, 1)">Следующая</button></li>'
        }
        
        let table = document.querySelector ('.table')

        let pagination = document.querySelector('ul.pagination')
        if (pagination === null) {
            pagination = document.createElement('ul')
        }
        
        pagination.className='pagination'
        pagination.setAttribute('maxPage', data.pageQuantity)

        pagination.innerHTML = buttonsHtml
        table.appendChild (pagination)

    }

    // получает экран товаров - page страницу
    // shift - нажата кнопка Следующая
    function buildScreen (page, shift = false, titleFilter = '') {
        if (shift != false) page = Number (document.querySelector('.active-pagination').getAttribute('page')) + shift
        ajax ('getWharehouse', {
            page:page,
            titleFilter: titleFilter
        }, function (result){
            // console.log (result)
            renderPage (result.data)
            renderPagination({
                currentPage:result.currentPage, 
                pageQuantity:result.pageQuantity
            })
        })
    }

    // нажата кнопка поиска
    function newSearch (reset = false) {
        let titleFilter = document.getElementById('filterTitle').value
        if (reset) titleFilter = ''
        
        buildScreen (1, false, titleFilter)
    }

    // нажата картинка
    function openImage (data) {
        let imageArrays = document.querySelector('input[name="imagesOfProduct_'+data.id+'"]').value
        turnONmodalGallery (JSON.parse (imageArrays))
        // turnONmodalImage(data.imagePath)
        turnONmodal()
    }

    document.addEventListener('DOMContentLoaded', function () {
        buildScreen (1)
    })
</script>