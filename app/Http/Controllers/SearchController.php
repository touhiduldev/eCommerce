<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function search(Request $request){

        $data = $request->all();

        $based_on = 'created_at';
        $order = 'DESC';

        if(!empty($data['sort']) && $data['sort'] != '' && $data['sort'] != 'undefined'){
            if ($data['sort'] == 1) {
                $based_on = 'product_name';
                $order = 'ASC';
            }elseif ($data['sort'] == 2) {
                $based_on = 'product_name';
                $order = 'DESC';
            }elseif ($data['sort'] == 3) {
                $based_on = 'after_discount';
                $order = 'ASC';
            }else{
                $based_on = 'after_discount';
                $order = 'DESC';
            }
        }

        $all_products = Product::where(function($q) use ($data){
            if (!empty($data['q']) && $data['q'] != '' && $data['q'] != 'undifined') {
                $q->where(function($q) use ($data){
                    $q->where('product_name', 'like', '%'.$data['q'].'%');
                    $q->orWhere('long_desp', 'like', '%'.$data['q'].'%');
                });
            }
            if(!empty($data['category_id']) && $data['category_id'] != '' && $data['category_id'] != 'undefined'){
                $q->where('category_id', $data['category_id']);
            }
            if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined' || !empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined'){
                $q->whereHas('rel_to_inventories', function ($q) use ($data){
                    if(!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined'){
                        $q->whereHas('rel_to_color', function ($q) use ($data){
                            $q->where('colors.id', $data['color_id']);
                        });
                    }

                    if(!empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined'){
                        $q->whereHas('rel_to_size', function ($q) use ($data){
                            $q->where('sizes.id', $data['size_id']);
                        });
                    }
                });
            }

            if(!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined' || !empty($data['max']) && $data['max'] != '' && $data['max'] != 'undefined'){
                $q->whereBetween('after_discount', [$data['min'], $data['max']]);
            }
        })->orderBy($based_on, $order)->get();


        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('frontend.search',[
            'all_products'=>$all_products,
            'categories'=>$categories,
            'colors'=>$colors,
            'sizes'=>$sizes,
        ]);
    }
}
