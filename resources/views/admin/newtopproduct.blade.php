{{-- $topProductEdit --}}

<link rel="stylesheet" href="{{ asset('css/newproduct.css') }}">
@extends('admin.app')
@section('content')
{{-- @if(Session::has('info'))
<div class="info">{{ Session::get('info') }}</div>
@endif --}}
<form action="{{ Route('updateTopProduct') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($topProductEdit)
    <input type="hidden" name="productId" value="{{ $topProductEdit->id }}">
    @endif


    <div class="input-block">
        @error('name')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="text" name="name" id='name'
            value="{{ old('name') ?? ($topProductEdit ? $topProductEdit->name:'') }}">
        @if(!$errors->has('name'))
        <label for="name">Название</label>
        @endif
    </div>

    <div class="input-block">
        @error('link')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="text" name="link" id='link'
            value="{{ old('link') ?? ($topProductEdit ? $topProductEdit->link:'') }}" placeholder="https://">
        @if(!$errors->has('link'))
        <label for="link">Ссылка</label>
        @endif
    </div>

    <div class="double-input-block">
        <div>
            @error('price')
            <div class="alert">{{ $message }}</div>
            @enderror
            <input type="text" name="price" id='price'
                value="{{ old('price')?? ($topProductEdit ? $topProductEdit->pricetext:'') }}">
            @if(!$errors->has('price'))
            <label for="price">Цена (от 5 990 р)</label>
            @endif
        </div>

        <div>
            @error('weight')
            <div class="alert">{{ $message }}</div>
            @enderror
            <input type="text" name="weight" id='weight'
                value="{{ old('weight') ?? ($topProductEdit ? $topProductEdit->weight:'') }}">
            @if(!$errors->has('weight'))
            <label for="weight">Вес, кг.</label>
            @endif
        </div>
    </div>

    @if($topProductEdit)
    <div class="edit-gallery-block">
        <div class="edit-gallery-item">
            <div class="edit-gallery-image" style="background-image: url({{ $topProductEdit->image }})"></div>
        </div>
    </div>
    @endif

    <div class="input-block">
        @error('image')
        <div class="alert">{{ $message }}</div>
        @enderror
        <input type="file" name="image" id="image" @if ($topProductEdit)
            onchange="document.querySelector('form').submit()" @endif>
        @if(!$errors->has('image'))
        <label for="image">Выберете фотографию (имя латиницей, img_01.jpg, img_02.jpg)</label>
        @endif

    </div>

    <div class="input-block">
        <input class="submit disabled" type="submit" value="Сохранить">
    </div>

</form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {


        // включаем кнопку Соханить
        let SaveButton = document.querySelector('input.submit')
        document.querySelector('form').querySelectorAll('input:not([type = "hidden"])').forEach(el => {
            el.oninput = function () {
                SaveButton.classList.remove('disabled')
            }
        })
    })

</script>