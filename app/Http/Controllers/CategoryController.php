<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    function category(){
        $trash_categories = Category::onlyTrashed()->get();
        $categories = Category::all();
        return view('admin.category.category',[
            'categories'=>$categories,
            'trash_categories'=>$trash_categories,
        ]);
    }

    function category_store(Request $request){
        $request->validate([
            'category_name'=>'required',
            'category_img'=>'required|mimes:jpg,bmp,png,webp|file|max:1024',
        ]);

        $id = Category::insertGetId([
            'category_name'=>$request->category_name,
            'icon'=>$request->icon,
            'added_by'=>Auth::id(),
            'created_at'=>Carbon::now(),
        ]);

        $category_image = $request->category_img;
        $extension = $category_image->getClientOriginalExtension();
        $file_name = Str::random(5).rand(100,999).'.'.$extension;
        $img = Image::make($category_image)->resize(450, 450)->save(public_path('uploads/category/'.$file_name));

        Category::find($id)->update([
            'category_img'=>$file_name,
        ]);
        return back()->with('added', 'Category Added Successfully!');
    }

    function category_edit($category_id){
        $category_info = Category::find($category_id);
        return view('admin.category.edit',[
            'category_info'=>$category_info,
        ]);
    }

    function category_update(Request $request){
        $category_img = $request->category_img;
        if ($category_img == null) {
            Category::find($request->category_id)->update([
                'category_name'=>$request->category_name,
            ]);
            return back()->with('success', 'Category updated successfully!');
        }else {
        $img = Category::where('id', $request->category_id)->first()->category_img;
        $delete_from = public_path('uploads/category/'.$img);
        unlink($delete_from);

        $category_image = $request->category_img;
        $extension = $category_image->getClientOriginalExtension();
        $file_name = Str::random(5).rand(100,999).'.'.$extension;
        $img = Image::make($category_image)->resize(450, 450)->save(public_path('uploads/category/'.$file_name));

        Category::find($request->category_id)->update([
            'category_name'=>$request->category_name,
            'category_img'=>$file_name,
        ]);
        return back()->with('success', 'Category updated successfully!');
        }
    }

    function category_dlt($category_id){
        // $img = Category::where('id', $category_id)->first()->category_img;
        // $delete_from = public_path('uploads/category/'.$img);
        // unlink($delete_from);
        Category::find($category_id)->delete();
        return back()->with('trashed', 'Category move to trash Successfully!');
    }

    function dlt_trashed($category_id){
        $img = Category::onlyTrashed()->where('id', $category_id)->first()->category_img;
        $delete_from = public_path('uploads/category/'.$img);
        unlink($delete_from);

        Category::onlyTrashed()->find($category_id)->forceDelete();
        return back()->with('deleted', 'Category Deleted Successfully!');
    }

    function category_restore($category_id){
        Category::onlyTrashed()->find($category_id)->restore();
        return back()->with('restored', 'Category Restored Successfully!');
    }
}
