<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    function add_coupon(){
        $coupons = Coupon::all();
        return view('admin.coupon.coupon',[
            'coupons'=> $coupons,
        ]);
    }

    function coupon(Request $request){
        Coupon::insert([
            'coupon_name'=> $request->coupon_name,
            'type'=> $request->type,
            'discount'=> $request->discount,
            'validity'=> $request->validity,
            'created_at'=> Carbon::now(),
        ]);
        return back();
    }

    function dlt_coupon($coupon_id){
        Coupon::find($coupon_id)->delete();
        return back();
    }
}
