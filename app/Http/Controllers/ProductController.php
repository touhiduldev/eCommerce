<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\Thumbnail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    function add_product(){
        $categories = Category::all();
        $subcategories = SubCategory::all();
        return view('admin.product.add_product',[
            'categories'=>$categories,
            'subcategories'=>$subcategories,
        ]);
    }

    function get_subcategory(Request $request){
        $subcategories = SubCategory::where('category_id', $request->category_id)->get();

        $str = '';

        foreach ($subcategories as $subcategory) {
            $str .= '<option value="'.$subcategory->id.'">'.$subcategory->subcategory_name.'</option>';
        }
        echo $str;
    }

    function product_store(Request $request){

        $product_id = Product::insertGetId([
            'category_id'=> $request->category_id,
            'subcategory_id'=> $request->subcategory_id,
            'brand'=> $request->brand,
            'product_name'=> $request->product_name,
            'price'=> $request->price,
            'discount'=> $request->discount,
            'after_discount'=> $request->price - ($request->price*$request->discount/100),
            'short_desp'=> $request->short_desp,
            'long_desp'=> $request->long_desp,
            'preview'=> $request->preview,
            'slug'=> Str::lower(str_replace(' ','-', $request->product_name)),

        ]);

        $preview_image = $request->preview;
        $extension = $preview_image->getClientOriginalExtension();
        $file_name = Str::random(6).rand(100,999).'.'.$extension;
        $imag = Image::make($preview_image)->resize(450,450)->save(public_path('uploads/products/preview/'.$file_name));

        Product::find($product_id)->update([
            'preview'=>$file_name,
        ]);

        foreach ($request->thumbnails as $thumbnails) {
            $extension = $thumbnails->getClientOriginalExtension();
            $file_name = Str::random(6).rand(100,999).'.'.$extension;
            $imag = Image::make($thumbnails)->resize(450,450)->save(public_path('uploads/products/thumbnails/'.$file_name));

            Thumbnail::insert([
                'product_id'=> $product_id,
                'thumbnail'=> $file_name,
                'created_at'=> Carbon::now(),
            ]);
        }

        return back();

    }

    function all_product(){
        $all_products = Product::all();
        $count = Product::count();
        return view('admin.product.all_products',[
            'all_products'=>$all_products,
            'count'=>$count,
        ]);
    }

    function product_dlt($product_id){
        $delete_preview = Product::where('id', $product_id)->first()->preview;
        $delete_from = public_path('uploads/products/preview/'.$delete_preview);
        unlink($delete_from);

        $thumbnails = Thumbnail::where('product_id', $product_id)->get();
        foreach ($thumbnails as $thumb) {
            $delete_thumb = Thumbnail::where('id', $thumb->id)->first()->thumbnail;
            $delete_thumb = public_path('uploads/products/thumbnails/'.$delete_thumb);
            unlink($delete_thumb);

            Thumbnail::find($thumb->id)->delete();
        }
            Product::find($product_id)->delete();
            return back();
    }

    function variation(){
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product.variation',[
            'colors'=> $colors,
            'sizes'=> $sizes,
        ]);
    }

    function variation_store(Request $request){

        if ($request->btn == 1) {
            Color::insert([
                'color_name'=>$request->color_name,
                'color_code'=>$request->color_code,
            ]);
            return back();
        }else {
            Size::insert([
                'size'=>$request->size,
            ]);
            return back();
        }

    }

    function inventory($product_id){
        $colors = Color::all();
        $sizes = Size::all();
        $product_info = Product::find($product_id);
        $inventories = Inventory::where('product_id',$product_id)->get();
        return view('admin.product.inventory',[
            'colors'=> $colors,
            'sizes'=> $sizes,
            'product_info'=> $product_info,
            'inventories'=> $inventories,
        ]);
    }

    function inventory_store(Request $request){
        Inventory::insert([
            'product_id'=>$request->product_id,
            'color_id'=>$request->color_id,
            'size_id'=>$request->size_id,
            'quantity'=>$request->quantity,
        ]);
        return back();
    }
}
