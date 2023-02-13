<?php

namespace App\Http\Controllers;

use App\Models\CustomerEmailVerify;
use App\Models\CustomerLogin;
use App\Models\Order;
use App\Models\PasswordReset;
use App\Notifications\CPRNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Notification;

class CustomerController extends Controller
{
    function customer_profile()
    {
        return view('frontend.myprofile');
    }

    function customer_profile_update(Request $request)
    {
        if ($request->new_password == '') {
            if ($request->photo == '') {
                CustomerLogin::find(Auth::guard('customerlogin')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                ]);
                return back()->with('updated', 'Profile updated successfully!');
                // 'password'=>bcrypt($request->new_password),
            } else {
                $profile_photo = $request->photo;
                $extension = $profile_photo->getClientOriginalExtension();
                $file_name = Auth::guard('customerlogin')->id() . '.' . $extension;
                $img = Image::make($profile_photo)->save(public_path('uploads/customer/' . $file_name));

                CustomerLogin::find(Auth::guard('customerlogin')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                    'photo' => $file_name,
                ]);
                return back()->with('updated', 'Profile updated successfully!');
            }
        } else {
            if ($request->photo == '') {
                CustomerLogin::find(Auth::guard('customerlogin')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->new_password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                ]);
                return back()->with('updated', 'Profile updated successfully!');
                // 'password'=>bcrypt($request->new_password),
            } else {
                $profile_photo = $request->photo;
                $extension = $profile_photo->getClientOriginalExtension();
                $file_name = Auth::guard('customerlogin')->id() . '.' . $extension;
                $img = Image::make($profile_photo)->save(public_path('uploads/customer/' . $file_name));

                CustomerLogin::find(Auth::guard('customerlogin')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->new_password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                    'photo' => $file_name,
                ]);
                return back()->with('updated', 'Profile updated successfully!');
            }
        }
    }

    function myorder()
    {
        $myorders = Order::where('customer_id', Auth::guard('customerlogin')->id())->get();
        return view('frontend.myorder', [
            'myorders' => $myorders,
        ]);
    }

    // PASSWORD RESET REQUEST SECTION

    function password_reset_request()
    {
        return view('password_resert.password_reset_request');
    }

    function reset_request_send(Request $request)
    {
        $customer = CustomerLogin::where('email', $request->email)->firstOrFail();
        PasswordReset::where('customer_id', $customer->id)->delete();

        $customer_info = PasswordReset::create([
            'customer_id' => $customer->id,
            'token' => uniqid(),
            'created_at' => Carbon::now(),
        ]);
        Notification::send($customer, new CPRNotification($customer_info));
        return back()->with('send', 'We just sent you an reset link! Please check your email and reset your password!');
    }

    function generate_new_pw_form($token)
    {
        return view('password_resert.pw_reset_form', [
            'token' => $token,
        ]);
    }

    function new_pw_generate(Request $request)
    {
        $customer = PasswordReset::where('token', $request->token)->firstOrFail();

        $request->validate([
            'password' => ['min:8|max:10|required|/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/|confirmed'],
            'password_confirmation' => 'required_with:password|same:password|min:8|max:10',
        ], [
            'password' => 'The password must be at least 8 character & maximum 10 character with a special character',
            'password_confirmation' => 'Your confirm password does not match with the password',
        ]);

        CustomerLogin::find($customer->customer_id)->update([
            'password' => bcrypt($request->password),
        ]);
        $customer->delete();
        return back()->with('success', 'Password generated');
    }

    function confirm_verify($token)
    {
        $customer = CustomerEmailVerify::where('token', $token)->firstOrFail();
        CustomerLogin::find($customer->customer_id)->update([
            'email_verified_at' => Carbon::now(),
        ]);
        $customer->delete();
        return back()->with('verified', 'Email verification success');
    }
}