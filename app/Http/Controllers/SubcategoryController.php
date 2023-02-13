<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SubcategoryController extends Controller
{
    function subcategory(){
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.subcategory.subcat',[
            'categories'=> $categories,
            'subcategories'=> $subcategories,
        ]);
    }

    function subcategory_store(Request $request){
        $id = SubCategory::insertGetId([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
        ]);

        $sub_ctgry_img = $request->subcategory_img;
        $extension = $sub_ctgry_img->getClientOriginalExtension();
        $file_name = Str::random(5).rand(100,999).'.'.$extension;
        $img = Image::make($sub_ctgry_img)->resize(500,350)->save(public_path('uploads/subcategory/'.$file_name));

        SubCategory::find($id)->update([
            'subcategory_img'=>$file_name,
        ]);
        return back();
    }

    function subcategory_edit($subcategory_id){
        $subcatgories = SubCategory::find($subcategory_id);
        $categorieys = Category::all();
        return view('admin.subcategory.edit',[
            'subcatgories'=> $subcatgories,
            'categorieys'=> $categorieys,
        ]);
    }

    function subcategory_update(Request $request){
        $subcategory_img = $request->subcategory_img;
        if ($subcategory_img == null) {
            SubCategory::find($request->subcategory_id)->update([
                'category_id'=>$request->category_id,
                'subcategory_name'=>$request->subcategory_name,
            ]);
            return back()->with('success', 'SubCategory Updated!');
        }else {
            $previous_image = SubCategory::where('id', $request->subcategory_id)->first()->subcategory_img;
            $delete_form = public_path('uploads/subcategory/'.$previous_image);
            unlink($delete_form);

            $subcategory_img = $request->subcategory_img;
            $extension = $subcategory_img->getClientOriginalExtension();
            $file_name = Str::random(5).rand(100,999).'.'.$extension;
            $img = Image::make($subcategory_img)->resize(450,450)->save('uploads/subcategory/'.$file_name);

            SubCategory::find($request->subcategory_id)->update([
                'category_id'=>$request->category_id,
                'subcategory_name'=>$request->subcategory_name,
                'subcategory_img'=>$file_name,
            ]);
            return back()->with('success', 'SubCategory Updated!');
        }
    }

    function subcategory_dlt($subcategory_id){
        $img = SubCategory::where('id', $subcategory_id)->first()->subcategory_img;
        $delete_from = public_path('uploads/subcategory/'.$img);
        unlink($delete_from);

        Subcategory::find($subcategory_id)->delete();
        return back()->with('deleted', 'SubCategory Deleted Successfully!');
    }
}
