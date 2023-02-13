<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    function checkout(){
        $total_item = Cart::where('customer_id', Auth::guard('customerlogin')->id())->count();
        $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
        $countries = Country::all();
        return view('frontend.checkout',[
            'total_item'=>$total_item,
            'carts'=>$carts,
            'countries'=>$countries,
        ]);
    }

    function getcountry(Request $request){
        $cities =  City::where('country_id', $request->country_id)->get();

        $str = '';

        foreach ($cities as $city) {
            $str .='<option value="'.$city->id.'">'.$city->name.'</option>';
        }
        echo $str;
    }

    function checkout_store(Request $request){

        if ($request->payment_method == 1) {
            //ORDER INFORMATION
            $latestOrder = Order::orderBy('created_at','DESC')->first();
            $order_number = '#'.str_pad($latestOrder->id + 1, 5, "0", STR_PAD_LEFT);
            Order::insert([
                'order_id' =>$order_number,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'sub_total'=>$request->sub_total,
                'total'=>$request->sub_total + $request->charge,
                'discount'=> $request->discount,
                'charge'=> $request->charge,
                'payment_method'=>$request->payment_method,
                'created_at'=>Carbon::now(),
            ]);

    // BILLING INFORMATION
            BillingDetails::insert([
                'order_id'=>$order_number,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'name'=>$request->name,
                'email'=>$request->email,
                'company'=>$request->company,
                'mobile'=>$request->phone,
                'address'=>$request->address,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'zip'=>$request->zip,
                'addi_info'=>$request->addi_info,
                'created_at'=>Carbon::now(),
            ]);

    // ORDER PRODUCT DETAILS
            $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();

            foreach ($carts as $cart) {
                OrderProduct::insert([
                    'order_id'=>$order_number,
                    'customer_id'=>Auth::guard('customerlogin')->id(),
                    'product_id'=>$cart->product_id,
                    'price'=>$cart->rel_to_product->after_discount,
                    'color_id'=>$cart->color_id,
                    'size_id'=>$cart->size_id,
                    'quantity'=>$cart->quantity,
                    'created_at'=>Carbon::now(),
                ]);

    // REMOVE QUANTITY
                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);

            }

    // INVOICE SEND TO THE EMAIL
            Mail::to($request->email)->send(new InvoiceMail($order_number));

    // SEND SMS to MOBILE

                // $url = "https://bulksmsbd.net/api/smsapi";
                // $api_key = "c6kH9EoW3x7Cov5NyDkW";
                // $senderid = "20touhidul";
                // $number = "$request->phone";
                // $message = "test sms check";

                // $data = [
                //     "api_key" => $api_key,
                //     "senderid" => $senderid,
                //     "number" => $number,
                //     "message" => $message
                // ];
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_POST, 1);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // $response = curl_exec($ch);
                // curl_close($ch);

                // $url = "http://66.45.237.70/api.php";
                // $number=$request->phone;
                // $text="Hello Esmail vai, Congratulations! You are selected for Fifa World CUP linesman. As Soon As Possible come on Qatar and join the program. Thank you!('Md Touhidul Islam')";
                // $data= array(
                // 'username'=>"01834833973",
                // 'password'=>"TE47RSDM",
                // 'number'=>"$number",
                // 'message'=>"$text"
                // );

                // $ch = curl_init(); // Initialize cURL
                // curl_setopt($ch, CURLOPT_URL,$url);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // $smsresult = curl_exec($ch);
                // $p = explode("|",$smsresult);
                // $sendstatus = $p[0];
    // REMOVE CART AFTER COMPLETE THE ORDER
            Cart::where('customer_id', Auth::guard('customerlogin')->id())->delete();

            return view('frontend.order_complete',[
                'order_number'=>$order_number,
            ]);

        }elseif ($request->payment_method == 2) {
            $data = $request->all();
            return redirect()->route('pay')->with('data', $data);
        }elseif ($request->payment_method == 3){
            $data = $request->all();
            return view('frontend.stripe',[
                'data'=>$data,
            ]);
        }else {
            $data = $request->all();
            return view('frontend.paypal',[
                'data'=>$data,
            ]);
        }

    }
}
