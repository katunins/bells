<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация администратора</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    @if (Session::has('info'))
    <div class="info">{{ Session::get('info') }}</div>
    @endif
    <div class="signin-container">

        <form action={{ $action }} role="form" method="post">
            @csrf

            @if ($action == 'setNewAdminPass')
            <h3>Установите пароль администратора</h3>
            @elseif ($action == 'signIn')
            <h3>Введите пароль администратора</h3>
            @endif

            @if (isset($changePass))
            <div class="form-block">
                @error ('password_old')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password_old" placeholder="Старый пароль">
            </div>
            <br>
            @endif


            <div class="form-block">
                @error ('password')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password" placeholder="Пароль">
            </div>

            @if ($action != 'signIn')
            <div class="form-block">
                @error ('passwordcheck')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password_confirmation" placeholder="Повторите пароль">
            </div>
            @endif

            @if ($action == 'signIn')
            <button type="submit">Войти</button>
            @else
            <button type="submit">Сохранить</button>
            @endif
        </form>

    </div>

</body>

</html>