<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WharehouseContoller;
use Illuminate\Support\Facades\Auth;
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
    // return view('welcome')->with('shop', ProductController::getProductToPage());
    return view('welcome')
    ->with('topProduct', WharehouseContoller::getTopProducts())
    ->with('filter', WharehouseContoller::getHelpTags())
    ->with('maxFilterList', 10);
});

Route::post('changeFilter', [ProductController::class, 'changeFilter']);
Route::post('changePage', [ProductController::class, 'changePage']);
Route::post('clearFilter', [ProductController::class, 'clearFilter']);
Route::post('getProductToPage', [ProductController::class, 'getProductToPage']);

// админ
Route::get('/admin', function () {
    return AdminController::checkAuth();
});

Route::post('setNewAdminPass', [AdminController::class, 'setNewAdminPass']);
Route::post('signIn', [AdminController::class, 'signIn']);

Route::get('/logOut', [AdminController::class, 'logOut']);
Route::get('/changePass', [AdminController::class, 'changePass']);

Route::get('/wharehouse', function () {
    if (Auth::check()) {
        return View('admin.wharehouse');
    } else {
        return redirect('/admin');
    }
});

Route::post('/getWharehouse', [WharehouseContoller::class, 'getWharehouseScreen']);
Route::post('/changeWharehousePage', [WharehouseContoller::class, 'changeWharehousePage']);

Route::get('/newProduct/{id?}', function ($id=null) {
    $productData = WharehouseContoller::getProductArr($id);
    if (Auth::check()) {
        if ($id && $productData) return View('admin.newproduct')->with('productEdit', true)->with('productData', $productData)->with('filter', WharehouseContoller::getHelpTags());
        else return View('admin.newproduct')->with('productEdit', false)->with('filter', WharehouseContoller::getHelpTags());
    } else {
        return redirect('/admin');
    }
});

Route::post('getNewProduct', [WharehouseContoller::class, 'getNewProduct'])->name('getNewProduct');
Route::post('changeProductQuantity', [WharehouseContoller::class, 'changeProductQuantity']);
Route::post('removeProduct', [WharehouseContoller::class, 'removeProduct']);
Route::post('changeProductImagesArray', [WharehouseContoller::class, 'changeProductImagesArray']);
Route::post('removeProductImage', [WharehouseContoller::class, 'removeProductImage']);

Route::post('addToCart', [CartController::class, 'addToCart']);
Route::get('/basket', [CartController::class, 'getCart']);
Route::post('changeCartProduct', [CartController::class, 'changeCartProduct']);
