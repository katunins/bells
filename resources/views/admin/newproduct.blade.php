<link rel="stylesheet" href="css/newproduct.css">
@extends('admin.app')
@section('content')
@if (Session::has('info'))
<div class="info">{{ Session::get('info') }}</div>
@endif
<form action="getNewProduct" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="input-block">
            @error('title')
                <div class="alert">{{ $message }}</div>
            @enderror
            <input type="text" name="title" id='title' value="{{ old('title') }}">
            @if(!$errors->has('title'))<label for="title">Заголовок</label>@endif
        </div>

        <div class="input-block">
            <textarea name="description" id="description" cols="30" rows="6">{{ old('description') }}</textarea>
            @if(!$errors->has('description'))<label for="description">Описание</label>@endif
            
        </div>
        
        <div class="double-input-block">
            <div>
                @error('price')
                    <div class="alert">{{ $message }}</div>
                @enderror
                <input type="text" name="price" id='price' value="{{ old('price') }}">
                @if(!$errors->has('price'))<label for="price">Цена, руб.</label>@endif
            </div>
    
            <div>
                @error('weight')
                    <div class="alert">{{ $message }}</div>
                @enderror
                <input type="text" name="weight" id='weight' value="{{ old('weight') }}">
                @if(!$errors->has('weight'))<label for="weight">Вес, кг.</label>@endif
            </div>
        </div>
        

        <div class="input-block">
            <textarea name="tags" id="tags" cols="30" rows="3" placeholder="Подарок на крестины, юбилей">{{ old('tags') }}</textarea>
            <label for="tags">Поисковые теги (через запятую)</label>
        </div>

        <div class="double-input-block">
        <div>
            @error('firstimage')
                <div class="alert">{{ $message }}</div>
            @enderror
            <input type="file" name="firstimage" id="firstimage">
            @if(!$errors->has('firstimage'))<label for="firstimage">Главная фотография</label>@endif
        </div>

        <div>
            @error('images')
                <div class="alert">{{ $message }}</div>
            @enderror
            @error('images.*')
                <div class="alert">{{ $message }}</div>
            @enderror
            <input type="file" name="images[]" id="images" multiple>
            @if(!$errors->has('images[]'))<label for="images[]">Детальные фотогарфии</label>@endif
        </div>
        </div>

        <div class="input-block">
            <input class="submit" type="submit" value="Сохранить">
        </div>
        
    </form>
@endsection