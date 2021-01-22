<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class TopProductsController extends Controller
{
    static function getTopProducts()
    {
        // dd (DB::table('topProduct')->get()->sortBy('sort'));
        return DB::table('topProduct')->get()->sortBy('sort');
    }

    static function getOneTopProduct($id)
    {
        return DB::table('topProduct')->where('id', $id)->first();
    }

    public function updateTopProduct(Request $request)
    //     "_token" => "jLLqoDNTIWDBaxhHO9MaZXNTT8dHn5TfnBFSWSTk"
    //   "productId" => "1"
    //   "title" => "Колокол1"
    //   "price" => "от 5 990"
    //   "weight" => "0.35"
    // "image" => Illuminate\Http\UploadedFile {#303 ▶}
    {

        $rules = [
            'name' => 'required|max:140',
            'link' => 'required',
            'price' => 'required',
            'weight' => 'required|numeric',

        ];
        $messages = [
            'name.required' => 'Укажите Заголовок',
            'link.required' => 'Укажите ссылку',
            'name.max:140' => 'В заголовке должно быть не более 140 символов',
            'price.required' => 'Укажите цену продукта',
            'weight.required' => 'Укажите вес продукта',
            'weight.numeric' => 'Укажите вес цифрами',
        ];

        if (!$request->productId) {
            // новый продукт - фотка обязательна
            $rules = array_merge($rules, [
                'image' => 'required|mimes:jpeg, jpg'
            ]);

            $messages = array_merge($messages, [
                'image.required' => 'Загрузите фотографию',
                'image.mimes' => 'Фотография должна быть jpg форматом',
            ]);
        };

        $request->validate($rules, $messages);
        $newData = [
            'name' => $request->name,
            'link' => $request->link,
            'pricetext' => $request->price,
            'weight' => $request->weight,
        ];

        if ($request->image) {
            $image = Image::make($request->file('image'));
            $image->resize(900, 900, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->sharpen(5);
            $image->save();
            $imagePath = $request->file('image')->store('public/topProducts');
            $newData['image'] = str_replace('public/', '/storage/', $imagePath);
        }



        if ($request->productId) {

            if ($request->image) {
                // удалим предыдущю фотографию
                $oldImagePath = DB::table('topProduct')->where('id', $request->productId)->first()->image;
                Storage::disk('public')->delete(explode('/storage/', $oldImagePath)[1]);
            }


            // обновим продукт
            DB::table('topProduct')->where('id', $request->productId)->update($newData);
        } else {
            // создадим новый
            $lastSort = DB::table('topProduct')->get()->max('sort');
            $newData['sort'] = $lastSort !== null ? $lastSort + 1 : 0;
            DB::table('topProduct')->insert($newData);
        }
        return redirect('/topProducts');
    }

    public function changeTopCard(Request $request)
    {

        dd($request->all());
    }

    // id: 
    // direct:
    public function shiftTopCard(Request $request)
    {
        if ($request->direction == 0) {
            DB::table('topProduct')->where('id', $request->id)->delete();
        } else {
            $topProduct = $this->getTopProducts();
            
            $countSort = (int)$request->sort;
            do {
                $countSort += (int)$request->direction;
                if ($topProduct->where('sort', $countSort)->count() > 0) {
                    $currentSort = (int)$request->sort;
                    $currentId = (int)$request->id;
                    $targetSort = $countSort;
                    $targetId = (int)$topProduct->where('sort', $countSort)->first()->id;
                    DB::table('topProduct')->where('id', $currentId)->update(['sort' => $targetSort]);
                    DB::table('topProduct')->where('id', $targetId)->update(['sort' => $currentSort]);
                    break;
                }
            } while ($countSort > 0 && $countSort < $topProduct->max('sort'));
        }
        return redirect()->back();
    }
}
