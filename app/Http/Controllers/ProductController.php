<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /*
    Вовзращает данные для карточки - подборка из лучших групп товаров или товаров
    @return Array
    [
        sort,
        main-image,
        name,
        price,
        weight,
        link
    ]
    */
    static function getTopProducts()
    {
        $result[] = [
            'sort' => 10,
            'main-image' => Storage::url('public/best-product-images/test-bell.jpg'),
            'name' => 'Антон покровитель',
            'price' => 5990,
            'weight' => '1',
            'link'=>'/'
        ];
    }
}
