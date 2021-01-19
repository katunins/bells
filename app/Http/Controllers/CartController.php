<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Products;

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
            'quantity'=>1,
            'created_at' => Carbon::now(),
            'stand'=>$request->stand
        ]);
        return redirect('basket');
    }

    static function getCart()
    {
        $collection = DB::table('cart')->where('userToken', session()->get('_token'))->get();
        if ($collection->count() > 0) {
            
            $collection->each(function ($item) {
                // dd (Products::whereId($item->id));
               $item->productParams = Products::whereId($item->productId)->first()->getAttributes();
            });
        }
        return View('basket')->with('collection', $collection);
    }

    static function getBasketSumm()
    {
        $data = DB::table('cart')->where('userToken', session()->get('_token'))->get();
        $summ = 0;
        if ($data) {
            foreach ($data as $item) {
                $summ+=$item->orderSumm*$item->quantity;
            }
        }
        return $summ;
    }

    public function changeCartProduct(Request $request)
    {
        $item = Products::whereId($request->id)->first();
        $item->quantity=3;
        $item->save();
        return response()->json($item);
    }
}
