<?php

namespace App\Http\Controllers;

use App\Models\BillingDetails;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function orders(){
        $orders = Order::all();
        return view('admin.order.order',[
            'orders'=>$orders,
        ]);
    }

    function order_status(Request $request){
        $after_explode = explode(',', $request->status);
        Order::where('order_id', $after_explode[0])->update([
            'status'=>$after_explode[1],
        ]);
        return back();
    }
}
