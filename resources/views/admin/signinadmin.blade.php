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
    <div class="signin-container">
        <form action="setNewAdminPass" role="form" method="post">
            @csrf
            {{-- {{ dd($update) }} --}}
            <h3>Установите пароль администратора</h3>

            @if (isset($update))
            <div class="form-block">
                @error ('password_old')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password_old" placeholder="Старый пароль">
            </div>
            @endif

            <br>
            <div class="form-block">
                @error ('password')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password" placeholder="Новый пароль">
            </div>
            <div class="form-block">
                @error ('passwordcheck')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input type="password" name="password_confirmation" placeholder="Повторите пароль">
            </div>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</body>

</html>