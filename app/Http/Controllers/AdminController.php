<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    static function checkAuth()
    {
        if (Auth::user() && Auth::user()->id == 1) {
            return View('admin.welcome');
        } else {
            return View('admin.signinadmin')->with('newAdmin');
        }
    }

    public function setNewAdminPass(Request $request)
    {
        $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        if ($request->input('password_old')) $rules['password_old'] = ['required'];
        $request->validate($rules, [
            'password_old.required' => 'Введите старый пароль',
            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        $user = new User();
        $user->password = Hash::make($request->input('password'));
        $user->email = 'useremail@something.com';
        $user->save();

        // if ($request->input('password_old')) {
        //     $pass = DB::table('adminpass')->get()[0]->password;
        //     if (!Hash::check($request->input('password_old'), $pass)) return redirect()->back()->withErrors(['password_old' => 'Введеный старый пароль - не верный!']);
        // }

        // DB::table('adminpass')->delete();
        // DB::table('adminpass')->insert(['password' => Hash::make($request->input('password'))]);
        // Session::put('auth', $request->input('password'));
        return redirect()->back();
    }
}
