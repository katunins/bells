<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class WharehouseContoller extends Controller
{
    public function __construct()
    {
        $this->maxGoodsOnPage = 3;
    }

    // Возвращает один экран с количеством нужного товара
    // page - нунжная страница
    // filterTtitle - фильтр
    public function getWharehouseScreen(Request $request)
    {

        $result = Products::where('title', 'like', '%' . $request->titleFilter . '%')
            ->orWhere('description', 'like', '%' . $request->titleFilter . '%')
            ->get()->chunk($this->maxGoodsOnPage);

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
        $request->validate([
            'title' => 'required|max:140',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'firstimage' => 'required|mimes:jpeg, jpg',
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpeg, jpg'
        ], [
            'title.required' => 'Укажите Заголовок',
            'title.max:140' => 'В заголовке должно быть не более 140 символов',
            'price.required' => 'Укажите цену продукта',
            'price.numeric' => 'Укажите стоимость цифрами',
            'weight.required' => 'Укажите вес продукта',
            'weight.numeric' => 'Укажите вес цифрами',
            'firstimage.required' => 'Загрузите главную фотографию',
            'firstimage.mimes' => 'Фотография должна быть jpg форматом',
            'images.required' => 'Загрузите фотографии',
            'images.*.mimes' => 'Фотографии должны быть jpg форматом',
        ]);

        $newProduct = new Products();
        $newProduct->title = $request->title;
        $newProduct->description = $request->description;
        $newProduct->price = $request->price;
        $newProduct->weight = $request->weight;
        $newProduct->tags = $request->tags;
        $newProduct->save();

        $productImagesFolder = 'public/productImages/';

        $firstImage = Image::make($request->file('firstimage'));
        $firstImage->resize(900, 900, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $firstImage->sharpen(5);
        $firstImage->save();
        $path = $request->file('firstimage')->store($productImagesFolder .$newProduct->id);
        $firstImagePath = str_replace('public/', 'storage/', $path);

        $iamgesPaths = [];

        foreach ($request->file('images') as $item) {
            $image = Image::make($item);
            $image->resize(900, 900, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->sharpen(5);
            $image->save();
            $path = $item->store($productImagesFolder . $newProduct->id);
            $iamgesPaths[] = str_replace('public/', 'storage/', $path);
        }
        
        Products::whereId($newProduct->id)->update(['images' => [
            'firstImage' => $firstImagePath,
            'imagesPaths' => $iamgesPaths
            ]
        ]);

        return redirect()->back()->with('info', '"' . $request->title . '" успешно создан');
    }
}
