<link rel="stylesheet" href="{{ asset('css/newproduct.css') }}">
@extends('admin.app')
@section('content')
@if(Session::has('info'))
<div class="info">{{ Session::get('info') }}</div>
@endif
<form action="{{ route ('getNewProduct') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($productEdit)
    <h2>Изменение товара "{{ $productData->title }}"</h2>
    <input type="hidden" name="productId" value="{{ $productData->id }}">
    <input type="hidden" name="productEdit" value=true>
    @endif


    <div class="input-block">
        @error('title')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="text" name="title" id='title'
            value="{{ old('title') ?? ($productEdit ? $productData->title:'') }}">
        @if(!$errors->has('title'))
        <label for="title">Заголовок</label>
        @endif
    </div>

    <div class="input-block">
        <textarea name="description" id="description" cols="30"
            rows="6">{{ old('description')?? ($productEdit ? $productData->description:'') }}</textarea>
        @if(!$errors->has('description'))
        <label for="description">Описание</label>
        @endif

    </div>
    {{-- "themes" => array:2 [▼
                "name" => "Тематика"
                "data" => array:2 [▼
                        0 => "Православный"
                        1 => "Настольный" --}}
    @foreach($filter as $filterCodeName => $filterGroup)
    <div class="input-block">
        <textarea name="filter[{{ $filterCodeName }}][data]" id="{{ $filterCodeName }}" cols="30"
            rows="4">{{ old($filterCodeName)?? ($productEdit ? implode("/", $productData->filter[$filterCodeName]):'') }}</textarea>
        <input type="hidden" name="filter[{{ $filterCodeName }}][name]" value="{{ $filterGroup['name'] }}">

        @if(!$errors->has($filterCodeName))
        <label for="{{ $filterCodeName }}">{{ $filterGroup['name'] }} (через слеш -
            Православные/Коробельные)</label>
        @endif
        <div class="theme-help-block">

            @foreach($filterGroup['data'] as $item)
            <button type="button" onclick="addWordToHelp(event)">+ <span>{{ $item }}</span>
            </button>
            @endforeach

        </div>
    </div>
    @endforeach


    <div class="double-input-block">
        <div>
            @error('price')
            <div class="alert">{{ $message }}</div>
            @enderror
            <input type="text" name="price" id='price'
                value="{{ old('price')?? ($productEdit ? $productData->price:'') }}">
            @if(!$errors->has('price'))
            <label for="price">Цена, руб.</label>
            @endif
        </div>

        <div>
            @error('weight')
            <div class="alert">{{ $message }}</div>
            @enderror
            <input type="text" name="weight" id='weight'
                value="{{ old('weight') ?? ($productEdit ? $productData->weight:'') }}">
            @if(!$errors->has('weight'))
            <label for="weight">Вес, кг.</label>
            @endif
        </div>
    </div>

    @if($productEdit)
    <div class="edit-gallery-block">
        @foreach($productData->images as $item)
        <div class="edit-gallery-item" index={{ $loop->index }}>
            <div class="edit-gallery-image" style="background-image: url({{ $item }})"></div>
            <div class="edit-gallery-buttons">
                <button type="button" onclick="changeGalleryImage(event, {{ $productData->id }}, -1)">←</button>
                <button type="button" onclick="changeGalleryImage(event, {{ $productData->id }}, 0)">x</button>
                <button type="button" onclick="changeGalleryImage(event, {{ $productData->id }}, 1)">→</button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="input-block">
        @error('images')
        <div class="alert">{{ $message }}</div>
        @enderror
        @error('images.*')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="file" name="images[]" id="images" multiple>
        @if(!$errors->has('images[]'))
        <label for="images[]">Добавьте фотографии (имя латиницей, img_01.jpg, img_02.jpg)</label>
        @endif

    </div>

    <div class="input-block">
        <input class="submit disabled" type="submit" value="Сохранить">
    </div>

</form>
@endsection

<script>
    function changeGalleryImage(event, productId, shift) {

        let imagesArr = []
        document.querySelectorAll('.edit-gallery-image').forEach(el => {
            imagesArr.push(el.style.backgroundImage.match(/url\(["']?([^"']*)["']?\)/)[1])
        })

        let currentElem = event.target.parentNode.parentNode

        let index = event.target.parentNode.parentNode.getAttribute('index')

        // shift - 0 - удаление
        if (shift == 0) {
            ajax('/removeProductImage', {
                    id: productId,
                    index: index
                }, function (result) {
                    currentElem.parentNode.removeChild(currentElem)
                    turnOFFSuperModal()
                })
            // turnONmodalMessage('Удалить фотографию у продукта?')
            // turnONmodalImage(imagesArr[index])
            // setOkModalButton(function () {
            //     ajax('/removeProductImage', {
            //         id: productId,
            //         index: index
            //     }, function (result) {
            //         currentElem.parentNode.removeChild(currentElem)
            //         turnOFFSuperModal()
            //     })
            // }, name = 'Да')
            // setCancelModalButton(cancelButtonCallback = turnOFFSuperModal, name = 'Отмена')
            // turnONmodal()

        } else {

            let newIndex = Number(index) + shift
            if (newIndex >= 0 && newIndex < imagesArr.length) {
                var newElem = (newIndex > index) ? currentElem.nextElementSibling : currentElem.previousElementSibling;

                let newImagesArr = imagesArr.slice(0)
                newImagesArr[index] = imagesArr[newIndex]
                newImagesArr[newIndex] = imagesArr[index]

                ajax('/changeProductImagesArray', {
                    newImagesArr: newImagesArr,
                    productId: productId,
                    removeImage: false
                }, function (result) {

                    if (result) {
                        if (newIndex > index) {
                            currentElem.parentNode.insertBefore(newElem, currentElem)
                        } else {
                            currentElem.parentNode.insertBefore(newElem, currentElem.nextSibling);
                        }
                        currentElem.setAttribute('index', newIndex)
                        newElem.setAttribute('index', index)
                    }
                })
            }
        }




    }

    function addWordToHelp(event) {
        let word = event.target.innerHTML
        let textareaElem = event.target.parentNode.parentNode.parentNode.querySelector('textarea')
        let currentText = textareaElem.value

        //если часть сложа уже записана - сотрем последнюю запись и слеш + первый слеш если есть
        if (event.target.parentNode.classList.contains('active-help-button')) {
            let tempWordsArr = currentText.split('/')
            tempWordsArr.pop()
            currentText = ''
            tempWordsArr.forEach(tempWord => {
                currentText += tempWord + '/'
            })
            currentText = currentText.substring(0, currentText.length - 1)
        }

        if (currentText != '') currentText += '/'
        textareaElem.value = currentText + word
        document.querySelector('input.submit').classList.remove('disabled')
        CheckIsHelpButton(textareaElem)
    }

    // подсветка кнопки подскази и скрытие лишних кнопок
    function CheckIsHelpButton(inputTextElem) {
        let inputWordsArr = inputTextElem.value.split('/')
        let lastWord = inputWordsArr[inputWordsArr.length - 1]
        let buttons = inputTextElem.parentNode.querySelector('.theme-help-block').querySelectorAll('button')
        buttons.forEach(el => {

            // if (lastWord!='') {
            if (el.classList.contains('hide')) el.classList.remove('hide');
            let buttonText = el.querySelector('span').innerHTML.toLowerCase()

            // скроем кнопку, если уже такая запись установлена
            inputWordsArr.forEach(item => {

                if (buttonText == item.toLowerCase()) {

                    el.classList.add('hide')
                }
            })

            // првоерим кнопку на содержание такого текста
            if (buttonText.indexOf(lastWord.toLowerCase()) >= 0 && lastWord != '') {
                el.classList.add('active-help-button')
            } else {
                if (el.classList.contains('active-help-button')) el.classList.remove('active-help-button');
            }
            // } else {
            //     if (el.classList.contains('active-help-button')) el.classList.remove('active-help-button');
            // }

        })

        // если наисано пар)
    }

    document.addEventListener('DOMContentLoaded', function () {


        // включаем кнопку Соханить
        let SaveButton = document.querySelector('input.submit')
        document.querySelector('form').querySelectorAll('input:not([type = "hidden"])').forEach(el => {
            el.oninput = function () {
                SaveButton.classList.remove('disabled')
            }
        })

        document.querySelectorAll('textarea:not([name="description"])').forEach(el => {

            // уберем лишние кнопки - подсказки
            // if (el.getAttribute('name') == 'themes' || el.getAttribute('name') == 'missions')
            //     CheckIsHelpButton(el)
            CheckIsHelpButton(el)
            el.oninput = function (event) {
                if (SaveButton.classList.contains('disabled')) SaveButton.classList.remove(
                    'disabled')
                CheckIsHelpButton(event.target)
            }
        })
    })

</script>