<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Кабинет администратора</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/general.js') }}"></script>
</head>

<body>

    <div class="container">

        <div class="menu-block">
            <div>
                <h3>Кабинет администратора</h3>
            </div>
            <div class="menu">
                <li><a href="/admin">Главная</a></li>
                <li><a href="/wharehouse">Склад</a></li>
                <li><a href="/newProduct">Добавить товар</a></li>
                <li><a href="/changePass">Изменить пароль</a></li>
                <li><a href="/logOut">Выход</a></li>
            </div>
        </div>
        <div class="work-area">
            @yield('content')
        </div>
    </div>
    @include('layouts.supermodal')
</body>

</html>