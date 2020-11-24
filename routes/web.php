<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome')->with('shop', new ProductController());
});

Route::post('changeFilter', [ProductController::class, 'changeFilter']);
Route::post('changePage', [ProductController::class, 'changePage']);
Route::post('clearFilter', [ProductController::class, 'clearFilter']);
Route::post('getProductToPage', [ProductController::class, 'getProductToPage']);

// админ
Route::get('/admin', function () {
    return AdminController::checkAuth ();
});

Route::post('setNewAdminPass', [AdminController::class,'setNewAdminPass']);