<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Кабинет администратора</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <div class="header">
        <h2>Кабинет администратора</h2>
    </div>
    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="">Товары</a></li>
                <br>
                <li><a href="">Изменить пароль администратора</a></li>
                <li><a href="">Выйти из администратора</a></li>
            </ul>
        </div>
        <div class="work-area">
            @yield('content')
        </div>
    </div>
</body>

</html>