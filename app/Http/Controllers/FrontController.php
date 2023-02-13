<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Inventory;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Size;
use App\Models\Thumbnail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use function Clue\StreamFilter\prepend;

class FrontController extends Controller
{
    function index()
    {

        // SEOTools::setTitle('');
        // SEOTools::setDescription('');

        $recent_viewed_product = json_decode(Cookie::get('recent_view'), true);
        if ($recent_viewed_product == null) {
            $recent_viewed_product = [];
            $after_unique = array_unique($recent_viewed_product);
        } else {
            $after_unique = array_unique($recent_viewed_product);
        }

        $recent_viewed_product = Product::find($after_unique)->take(3);
        $featured_products = Product::orderBy('created_at', 'DESC')->get()->take(3);

        // echo Cookie::get('recent_view');
        $categories = Category::all();
        $products = Product::paginate(4);
        $top_selling_products = OrderProduct::groupBy('product_id')
            ->selectRaw('sum(quantity) as sum, product_id')
            ->orderBy('quantity', 'DESC')
            ->havingRaw('sum >= 5')
            ->get();
        return view('frontend.index', [
            'categories' => $categories,
            'products' => $products,
            'top_selling_products' => $top_selling_products,
            'recent_viewed_product' => $recent_viewed_product,
            'featured_products' => $featured_products,
        ]);
    }

    function categories_product($category_id)
    {
        $category_products = Product::where('category_id', $category_id)->get();
        $categories = Category::find($category_id);
        return view('frontend.category_product', [
            'category_products' => $category_products,
            'categories' => $categories,
        ]);
    }

    function product($slug_id)
    {

        $product_info = Product::where('slug', $slug_id)->get();
        $related_products = Product::where('category_id', $product_info->first()->category_id)->where('id', '!=', $product_info->first()->id)->get();
        $thumbnails = Thumbnail::where('product_id', $product_info->first()->id)->get();
        $available_colors = Inventory::where('product_id', $product_info->first()->id)->groupBy('color_id')->selectRaw('count(*) as total, color_id')->get();
        $available_sizes = Size::all();
        $reviews = OrderProduct::where('product_id', $product_info->first()->id)->whereNotNull('review')->get();
        $total_reviews = OrderProduct::where('product_id', $product_info->first()->id)->whereNotNull('review')->count();
        $total_stars = OrderProduct::where('product_id', $product_info->first()->id)->whereNotNull('review')->sum('star');

        $product_id = $product_info->first()->id;
        $al = Cookie::get('recent_view');
        if (!$al) {
            $al = "[]";
        }
        $all_info = json_decode($al, true);
        $all_info = Arr::prepend($all_info, $product_id);
        $recent_product_id = json_encode($all_info);
        Cookie::queue('recent_view', $recent_product_id, 1000);

        return view('frontend.product', [
            'product_info' => $product_info,
            'thumbnails' => $thumbnails,
            'available_colors' => $available_colors,
            'available_sizes' => $available_sizes,
            'related_products' => $related_products,
            'reviews' => $reviews,
            'total_reviews' => $total_reviews,
            'total_stars' => $total_stars,
        ]);
    }

    function getsize(Request $request)
    {
        $sizes = Inventory::where('product_id', $request->product_id)->where('color_id', $request->color_id)->get();
        $str = '';

        foreach ($sizes as $size) {
            $str .= '<div class="form-check size-option form-option form-check-inline mb-2">
                        <input class="form-check-input" ' . ($size->rel_to_size->id == 1 ? "checked" : "") . ' type="radio" name="size_id" id="' . $size->rel_to_size->id . '" value="' . $size->rel_to_size->id . '">
                        <label class="form-option-label" for="' . $size->rel_to_size->id . '">' . $size->rel_to_size->size . '</label>
                    </div>';
        }
        echo $str;
    }

    function customer_reg_login()
    {
        return view('frontend.customer_reg_log');
    }

    // Cart section

    function cart(Request $request)
    {

        $coupon = $request->coupon;
        $message = null;
        $type = '';
        $get_discount = '';

        if ($coupon == '') {
            $discount = 0;
        } else {
            if (Coupon::where('coupon_name', $coupon)->exists()) {
                if (Carbon::now()->format('Y-m-d') > Coupon::where('coupon_name', $coupon)->first()->validity) {
                    $discount = 0;
                    $message = 'Coupon code is expired!';
                } else {
                    $discount = Coupon::where('coupon_name', $coupon)->first()->discount;
                    $type = Coupon::where('coupon_name', $coupon)->first()->type;
                    $get_discount = 'Congrats!! You get a discount!';
                }
            } else {
                $discount = 0;
                $message = 'Coupon code is invalid!';
            }
        }

        $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
        return view('frontend.cart', [
            'carts' => $carts,
            'discount' => $discount,
            'message' => $message,
            'type' => $type,
            'get_discount' => $get_discount,
        ]);
    }

    function shop()
    {
        $all_products = Product::all();
        $all_categories = Category::all();
        $all_colors = Color::all();
        $all_sizes = Color::all();
        return view('frontend.shop', [
            'all_products' => $all_products,
            'all_categories' => $all_categories,
            'all_colors' => $all_colors,
            'all_sizes' => $all_sizes,
        ]);
    }

    function review_store(Request $request)
    {
        OrderProduct::where('customer_id', $request->customer_id)->where('product_id', $request->product_id)->update([
            'review' => $request->review,
            'star' => $request->star,
        ]);
        return back();
    }
}