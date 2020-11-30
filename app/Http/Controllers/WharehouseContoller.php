<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class WharehouseContoller extends Controller
{

    // если такого хелпслова нет в базе - добавляет
    private function checkNewHelpThemes($helpThemes) {
        $currentThemes = $this->getHelpTags()['themes']->all();
        foreach ($helpThemes as $item) {
            if (array_search($item, $currentThemes)===false) {
                $new[]=$item;
                DB::table('ProductThemes')->insert(['theme'=>$item]);
            }
        }
    }

    public function __construct()
    {
        $this->maxGoodsOnPage = 3;
        $this->productImagesFolder = 'public/productImages/';
    }

    // Возвращает один экран с количеством нужного товара
    // page - нунжная страница
    // filterTtitle - фильтр
    public function getWharehouseScreen(Request $request)
    {
        
        $result = Products::where('title', 'like', '%' . $request->filter['title'] . '%')
            // ->orWhere('description', 'like', '%' . $request->filter['title'] . '%')
            ->whereJsonContains('themes', '*')
            ->get()->chunk($this->maxGoodsOnPage);

        // dd ($result);

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
        if ($request->themes != '') {
            if (strpos($request->themes, '/') === false) {
                // одно слово
                $helpThemes []= $request->themes;
            } else {
                $helpThemes = explode('/', $request->themes);
            }

            $this->checkNewHelpThemes($helpThemes);

        } else {
            $helpThemes = [];
        }
        $request->validate($rules, $messages);


        if ($request->productEdit) {
            $product = Products::whereId($request->productId)->first();
            $updateArr = [];

            if ($product->title != $request->title) $updateArr['title'] = $request->title;
            if ($product->description != $request->description) $updateArr['description'] = $request->description;
            if ($product->price != $request->price) $updateArr['price'] = $request->price;
            if ($product->weight != $request->weight) $updateArr['weight'] = $request->weight;
            
            $updateArr['themes'] = $helpThemes;

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
            $newProduct->themes = $helpThemes;
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

    static function getHelpTags()
    {
        $helpTags = [
            'themes' => DB::table('ProductThemes')->pluck('theme'),
            'mission' => DB::table('ProductMissions')->pluck('mission')
        ];
        return  $helpTags;
    }
}
