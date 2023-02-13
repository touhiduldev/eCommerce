<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    function add_wishlist(Request $request)
    {

        Wishlist::insert([
            'customer_id' => Auth::guard('customerlogin')->id(),
            'product_id' => $request->product_id,
            'created_at' => Carbon::now(),
        ]);
        return back()->with('wishlist', 'Your selected product added on wishlist!');
    }

    function remove_wishlist($wishlist_id)
    {
        Wishlist::find($wishlist_id)->delete();
        return back()->with('wishlist_removed', 'Wishlist removed successfully!');
    }
}