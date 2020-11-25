<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Перераспределяет маршруты при переходе на /admin
    static function checkAuth()
    {   
        if (Auth::check()) {
            return View('admin.welcome');
        } elseif (User::where('email', '=', 'admin')->exists()) {
            return View('admin.signinadmin')->with('action', 'signIn');
        } else {
            return View('admin.signinadmin')->with('action', 'setNewAdminPass');
        }
    }

    // Выход из юзера
    public function logOut()
    {
        Auth::logout();
        return redirect('/');
    }

    // password /  password_old
    // Замена пароля
    public function changePass(Request $request)
    {
        if (Auth::check()) {
            return View('admin.signinadmin')->with('action', 'setNewAdminPass')->with('changePass', true);
        } else {
            return redirect('/admin');
        }
    }

    // password
    // устанавливает пароль нового администратора
    public function setNewAdminPass(Request $request)
    {
        $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        if ($request->exists('password_old')) $rules['password_old'] = ['required'];
        $request->validate($rules, [
            'password_old.required' => 'Введите старый пароль',
            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        if ($request->exists('password_old')) {

            $user = User::where('email', 'admin')->first();
            if (Hash::check($request->password_old, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::logout();
                return redirect('/admin')->with('info', 'Пароль успешно изменен!');
            } else {
                return redirect()->back()->withErrors(['password_old'=>'Введенный старый пароль не верный!']);
            }
        } else {
            User::create([
                'name' => 'admin',
                'email' => 'admin',
                'password' => Hash::make($request->input('password')),
            ]);
            return redirect('/admin')->with('action', 'signin')->with('info', 'Пароль успешно создан!');
        }

        
    }

    // password
    // Авторизация
    public function signIn(Request $request)
    {
        if (Auth::attempt(['email' => 'admin', 'password' => $request->password])) {
            return redirect()->back()->with('action', 'signin')->with('info', 'Пароль успешно установлен!');
        } else {
            return redirect()->back()->with('action', 'signin')->withErrors(['password'=> 'Не верный пароль!']);
        }
    }
}
