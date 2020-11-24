<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function __construct()
    {
        // Популярные товары
        $this->topProducts = [
            [
                'sort' => 10,
                'image' => Storage::url('public/best-product-images/test-bell.jpg'),
                'name' => 'Антон покровитель',
                'price' => 5990,
                'weight' => '1',
                'link' => '/'
            ], [
                'sort' => 20,
                'image' => Storage::url('public/best-product-images/test-bell.jpg'),
                'name' => 'Павел покровитель',
                'price' => 1990,
                'weight' => '1',
                'link' => '/'
            ], [
                'sort' => 30,
                'image' => Storage::url('public/best-product-images/test-bell.jpg'),
                'name' => 'Павел покровитель',
                'price' => 2990,
                'weight' => '1',
                'link' => '/'
            ], [
                'sort' => 40,
                'image' => Storage::url('public/best-product-images/test-bell.jpg'),
                'name' => 'Павел покровитель',
                'price' => 2990,
                'weight' => '1',
                'link' => '/'
            ]
        ];

        // Фильтры магазина
        $this->filterState = [
            [
                'theme' => 'Тематика',
                'sort' => 10,
                'filters' => [
                    ['id' => 1, 'name' => 'Имена', 'checked' => false],
                    ['id' => 2, 'name' => 'Православные', 'checked' => false],
                    ['id' => 3, 'name' => 'Коробельные', 'checked' => false],
                    ['id' => 4, 'name' => 'Памятники', 'checked' => false],
                    ['id' => 5, 'name' => 'Личности', 'checked' => false],
                ],
            ], [
                'theme' => 'Назначение',
                'sort' => 20,
                'filters' => [
                    ['id' => 6, 'name' => 'Подарок на крестины', 'checked' => false],
                    ['id' => 7, 'name' => 'Подарок на юбилей', 'checked' => false],
                    ['id' => 8, 'name' => 'Подарок руководителю', 'checked' => false],
                ]
            ], [
                'theme'  => 'Размер',
                'sort' => 30,
                'filters' => [
                    ['id' => 9, 'name' => 'Настольный, 500 гр.', 'checked' => false],
                    ['id' => 10, 'name' => 'Настольный, 1 кг.', 'checked' => false],
                    ['id' => 11, 'name' => 'Большой колокол, 4 кг.', 'checked' => false],
                ]
            ]
        ];

        // Фильтр цены
        $this->filterPrice = [
            'min' => 1100,
            'max' => 5990
        ];

        // Страницы магазина
        $this->pageState = [
            [
                'num' => 1,
                'active' => false
            ], [
                'num' => 2,
                'active' => false
            ], [
                'num' => 3,
                'active' => true
            ], [
                'num' => 4,
                'active' => false
            ],
        ];

        $this->productsPage = [
            [
                'image' => 'images/test-product-bell.jpg',
                'title' => 'Георгий победоносец',
                'description' => 'Малый колокол в честь Георгия победоносца. Тыльная сторона оформлена рисунком скульптора.',
                'price' => 5990,
                'weight' => 1
            ],[
                'image' => 'images/test-product-bell.jpg',
                'title' => 'Георгий победоносец',
                'description' => 'Малый колокол в честь Георгия победоносца. Тыльная сторона оформлена рисунком скульптора.',
                'price' => 5990,
                'weight' => 1
            ],[
                'image' => 'images/test-product-bell.jpg',
                'title' => 'Георгий победоносец',
                'description' => 'Малый колокол в честь Георгия победоносца. Тыльная сторона оформлена рисунком скульптора.',
                'price' => 5990,
                'weight' => 1
            ],[
                'image' => 'images/test-product-bell.jpg',
                'title' => 'Георгий победоносец',
                'description' => 'Малый колокол в честь Георгия победоносца. Тыльная сторона оформлена рисунком скульптора.',
                'price' => 5990,
                'weight' => 1
            ],
        ];
    }

    // Возвращает товары для одной страницы
    function getProductToPage($maxProduct = 9)
    {
        return response()->json($this->productsPage);
    }

    // id - фильтра
    // меняет checked
    public function changeFilter(Request $request)
    {
        if (isset($request->id)) {
            foreach ($this->filterState as $key1 => $val1) {
                foreach ($val1['filters'] as $key2 => $val2) {
                    if ($val2['id'] == $request->id) {
                        $this->filterState[$key1]['filters'][$key2]['checked'] = !$val2['checked'];
                        break (2);
                    }
                }
            }
            return $this->getProductToPage();
        }
    }

    // {num выбранный номер страницы}
    public function changePage(Request $request)
    {
        if (isset($request->num)) {
            foreach ($this->pageState as $key => $item) {
                if ($item['num'] == $request->num) {
                    $this->pageState[$key]['active'] = true;
                } else {
                    $this->pageState[$key]['active'] = false;
                }
            }
            return $this->getProductToPage();
        }
    }

    // возвращает фильтры в стандартное положение
    public function clearFilter()
    {
        $this->__construct();
        return $this->getProductToPage();
    }
}
