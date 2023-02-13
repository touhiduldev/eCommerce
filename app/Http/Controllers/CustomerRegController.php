<?php

namespace App\Http\Controllers;

use App\Models\CustomerEmailVerify;
use App\Models\CustomerLogin;
use App\Notifications\CEVNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerRegController extends Controller
{
    function customer_store(Request $request){
        $request->validate([
            'name'=> 'required',
            'email'=> 'required',
            'password'=> 'required|min:8|max:10|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
        ]);
        CustomerLogin::insert([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'created_at'=> Carbon::now(),
        ]);

        $customer = CustomerLogin::where('email', $request->email)->firstOrFail();

        $customer_info = CustomerEmailVerify::create([
            'customer_id'=>$customer->id,
            'token'=>uniqid(),
            'created_at'=>Carbon::now(),
        ]);

        Notification::send($customer, new CEVNotification($customer_info));

        return back()->withSuccess('Please check your email and confirm it');




        // if(Auth::guard('customerlogin')->attempt(['email'=>$request->email, 'password'=>$request->password])){
        //     return redirect('/');
        // }
    }
}
