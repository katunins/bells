<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // "_token" => "WEYxYR7v8nDKoThZHI9ma8CLFIyl6E9abEdJkVig"
    // "stand" => "Дуб"
    // "orderSumm" => "7290"
    // "productId" => "1"
    public function addToCart(Request $request)
    {
        DB::table('cart')->insert([
            'userToken'=>$request->_token,
            'productId'=>$request->productId,
            'orderSumm'=>$request->orderSumm,
            'created_at' => Carbon::now(),
            'stand'=>$request->stand
        ]);
        return redirect('basket');
    }

    static function getCart()
    {
        return View('basket')->with('cart', DB::table('cart')->where('userToken', session()->get('_token'))->get());
    }
}
