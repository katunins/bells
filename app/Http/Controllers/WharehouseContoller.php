<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;

class WharehouseContoller extends Controller
{
    public function __construct()
    {
        $this->maxGoodsOnPage =3;
    }

    // Возвращает один экран с количеством нужного товара
    // page - нунжная страница
    // filterTtitle - фильтр
    public function getWharehouseScreen(Request $request)
    {
        // $result = Products::where([
        //     ['price','<=',$request->filter['maxPrice']],
        //     ['price','>=',$request->filter['minPrice']],
        //     ['title','like','%'.$request->filter['title'].'%'],
        // ])->get()->chunk($this->maxGoodsOnPage);

        $result = Products::where('title','like','%'.$request->titleFilter.'%')
        ->orWhere('description','like','%'.$request->titleFilter.'%')
        ->get()->chunk($this->maxGoodsOnPage);
    
        foreach ($result as $key => $chunk) {
            $result[$key] = array_values($chunk->toArray());
        }
        if ($result->count() == 0) {
            return response()->json([
                'data'=>$result, 
                'pageQuantity'=>$result->count(), 
                'currentPage'=>$request->page,
                ]);
        } else {
            return response()->json([
                'data'=>$result[$request->page-1], 
                'pageQuantity'=>$result->count(), 
                'currentPage'=>$request->page,
                ]);
        }
    }

}
