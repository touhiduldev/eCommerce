<?php

namespace App\Http\Controllers;

use App\Models\CustomerLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLoginController extends Controller
{
    function customer_login(Request $request){
        $request->validate([
            '*'=>'required',
        ],[
            '*'=>'Please verify that you are not a robot.',
        ]);
        if(Auth::guard('customerlogin')->attempt(['email'=>$request->email, 'password'=>$request->password])){
            if (Auth::guard('customerlogin')->user()->email_verified_at == null) {
                return redirect()->route('customer.reglogin')->withWarning('Please verify your email before login your account!');
            }else{
                return redirect('/');
            }
        }else{
            return redirect()->route('customer.reglogin');
        }
    }

    function customer_logout(){
        Auth::guard('customerlogin')->logout();
        return redirect()->route('customer.reglogin');
    }
}
