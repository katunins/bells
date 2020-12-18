<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class WharehouseContoller extends Controller
{



    public function __construct()
    {
        $this->maxGoodsOnPage = 9;
        $this->productImagesFolder = 'public/productImages/';
    }

    // если такого хелпслова нет в базе - добавляет
    private function checkNewHelpThemes($filter, $originalFilter)
    {
        foreach ($filter as $filterGroupName => $textdata) {


            $currentFilters = $this->getHelpTags()[$filterGroupName]['data'];
            // dd ($this->getHelpTags());
            foreach ($textdata as $item) {
                // dd($this->getHelpTags()[$filterGroupName]);
                if (count($currentFilters) == 0 || array_search($item, $currentFilters) === false) {
                    DB::table('filters')->insert(
                        [
                            'code' => $filterGroupName,
                            'codeName' => $originalFilter[$filterGroupName]['name'], //нужна для записи Названия раздела
                            'name' => $item
                        ]
                    );
                }
            }
        }
    }

    // Возвращает один экран с количеством нужного товара
    // page - нунжная страница
    // фильтр по которому фильруется
    // array:2 [
    //     "page" => 1
    //     "filter" => array:3 [
    //       "title" => null
    //       "themes" => []
    //       "mission" => []
    //     ]
    //   ]
    public function getWharehouseScreen(Request $request)
    {


        $result = Products::where('title', 'like', '%' . $request->filter['title'] . '%')
            ->orWhere('description', 'like', '%' . $request->filter['title'] . '%')
            ->get();

        // Отфильтруем
        // $prod - один продукт
        foreach ($result as $productKey => $prod) {

            $findResult = 1; //по умолчанию если фильтров нет - true
            foreach ($request->filter as $filterName => $filterItem) {
                if ($filterName == 'title') continue;
                foreach ($filterItem as $item) {

                    $findResult *= array_search($item, $prod->filter[$filterName])=== false ? 0 : 1;
                }
            }

            if ($findResult == 0) $result->forget($productKey);
        }


        $result = $result->chunk($this->maxGoodsOnPage);


        foreach ($result as $key => $chunk) {
            $result[$key] = array_values($chunk->toArray());
        }
        // dd ($result);
        if ($result->count() == 0) {
            return response()->json([
                'data' => $result,
                'pageQuantity' => $result->count(),
                'currentPage' => $request->page,
            ]);
        } else {
            return response()->json([
                'data' => $result[$request->page - 1],
                'pageQuantity' => $result->count(),
                'currentPage' => $request->page,
            ]);
        }
    }

    // принимает данные и записывает в базу
    // "title" => "Андрей - покровитель"
    // "description" => "Настольный колокол с молитвой святого Андрея на тыльной стороне. Подходит в подарок на юбилей, крестины ребенку, крестнику"
    // "themes" => "Православные/Стильные/Красивые"
    // "mission" => "Подарок на крестины/Подарок на именины"
    // "price" => "5990"
    // "weight" => "0.35"
    public function getNewProduct(Request $request)
    {

        $rules = [
            'title' => 'required|max:140',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',

        ];
        $messages = [
            'title.required' => 'Укажите Заголовок',
            'title.max:140' => 'В заголовке должно быть не более 140 символов',
            'price.required' => 'Укажите цену продукта',
            'price.numeric' => 'Укажите стоимость цифрами',
            'weight.required' => 'Укажите вес продукта',
            'weight.numeric' => 'Укажите вес цифрами',
        ];

        // Если отправлена форма редактирования
        if (!isset($request->productEdit)) {
            $rules = array_merge($rules, [
                'images' => 'required|array',
                'images.*' => 'required|mimes:jpeg, jpg'
            ]);

            $messages = array_merge($messages, [
                'images.required' => 'Загрузите фотографии',
                'images.*.mimes' => 'Фотографии должны быть jpg форматом',
            ]);
        }

        // работаем с фильтром
        foreach ($request->filter as $filterGroupName => $textdata) {
            if ($textdata['data'] != '') {
                if (strpos($textdata['data'], '/') === false) {
                    // одно слово
                    $filter[$filterGroupName][] = $textdata['data'];
                } else {
                    $filter[$filterGroupName] = explode('/', $textdata['data']);
                }
            } else {
                $filter[$filterGroupName] = [];
            }
        }
        $this->checkNewHelpThemes($filter, $request->filter);


        $request->validate($rules, $messages);


        if ($request->productEdit) {
            $product = Products::whereId($request->productId)->first();
            $updateArr = [];

            if ($product->title != $request->title) $updateArr['title'] = $request->title;
            if ($product->description != $request->description) $updateArr['description'] = $request->description;
            if ($product->price != $request->price) $updateArr['price'] = $request->price;
            if ($product->weight != $request->weight) $updateArr['weight'] = $request->weight;

            $updateArr['filter'] = $filter;

            if ($request->images) {
                $imagesPaths = [];
                foreach ($request->file('images') as $item) {
                    $image = Image::make($item);
                    $image->resize(900, 900, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->sharpen(5);
                    $image->save();
                    $path = $item->store($this->productImagesFolder . $request->productId);
                    $imagesPaths[] = str_replace('public/', '/storage/', $path);
                }
                $updateArr['images'] = array_merge($product->images, $imagesPaths);
            }
            Products::whereId($request->productId)->update($updateArr);
            return redirect()->back();
        } else {
            $newProduct = new Products();
            $newProduct->title = $request->title;
            $newProduct->description = $request->description;
            $newProduct->price = $request->price;
            $newProduct->weight = $request->weight;
            $newProduct->filter = $filter;
            $newProduct->save();


            $imagesPaths = [];

            foreach ($request->file('images') as $item) {
                $image = Image::make($item);
                $image->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->sharpen(5);
                $image->save();
                $path = $item->store($this->productImagesFolder . $newProduct->id);
                $imagesPaths[] = str_replace('public/', '/storage/', $path);
            }

            Products::whereId($newProduct->id)->update([
                'images' => $imagesPaths,
                // 'themes' => $helpThemes
            ]);
            return redirect('newProduct/' . $newProduct->id);
        }
    }

    public function changeProductQuantity(Request $request)
    {
        Products::whereId($request->id)->update(['quantity' => $request->newQuantity]);
        return response()->json(true);
    }

    public function removeProduct(Request $request)
    {
        Products::whereId($request->id)->delete();
        Storage::deleteDirectory($this->productImagesFolder . $request->id);
        return response()->json(true);
    }

    static function getProductArr($id)
    {
        return Products::whereId($id)->first();
    }

    // newImagesArr
    // productId
    public function changeProductImagesArray(Request $request)
    {
        Products::whereId($request->productId)->update(['images' => $request->newImagesArr]);
        return response()->json(true);
    }

    // id:productId, index:index
    public function removeProductImage(Request $request)
    {
        $imageArray = Products::whereId($request->id)->first()->images;
        $filePath = str_replace('/storage/', '/public/', $imageArray[$request->index]);
        Storage::delete($filePath);

        $newImaageArray = [];
        foreach ($imageArray as $key => $val) {
            if ($key != $request->index) $newImaageArray[] = $val;
        }

        unset($imageArray[$request->index]);
        Products::whereId($request->id)->update(['images' => $newImaageArray]);
        return response()->json(true);
    }

    // Возвращает названия фильтров
    static function getHelpTags()
    {
        $filters = DB::table('filters')->get();
        $result = $result = [
            'themes' => [
                'name' => 'Тематика',
                'data' => [],
            ],
            'mission' => [
                'name' => 'Направление',
                'data' => [],
            ],
        ];
        if ($filters->count() > 0) {
            foreach ($filters as $item) {
                // нет такого ключа
                if (!isset($result[$item->code])) {
                    $result[$item->code]['name'] = $item->codeName;
                    $result[$item->code]['data'][] = $item->name;
                } else {
                    $result[$item->code]['data'][] = $item->name;
                }
            }
        }

        return  $result;
    }

    static function getTopProducts()
    {
        return [
            [
                'image' => 'images/test-bell.jpg',
                'link' => '',
                'name' => 'Колокол',
                'price' => 5990,
                'weight' => 0.35,
            ]
        ];
    }
}
