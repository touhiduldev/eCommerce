<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    function profile(){
        return view('admin.profile.profile');
    }

    function update_profile(Request $request){
        $image = Auth::user()->image;
        if ($image == null) {

            $profile_image = $request->image;
            $extension = $profile_image->GetClientOriginalExtension();
            $file_name = Str::random(5).rand(100,999).'.'.$extension;
            $img = Image::make($profile_image)->resize(400,420)->save(public_path('uploads/profile/'.$file_name));

            User::find(Auth::id())->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'image'=>$file_name,
            ]);
            return back()->with('updated', 'User Profile Update Successfully!');
        }else {

            $delete_from = public_path('uploads/profile/'.$image);
            unlink($delete_from);

            $profile_image = $request->image;
            $extension = $profile_image->GetClientOriginalExtension();
            $file_name = Str::random(5).rand(100,999).'.'.$extension;
            $img = Image::make($profile_image)->resize(400,420)->save(public_path('uploads/profile/'.$file_name));

            User::find(Auth::id())->update([
                'image'=>$file_name,
            ]);
            return back()->with('updated', 'User Profile Update Successfully!');
        }
    }

    function password(){
        return view('admin.profile.password');
    }

// USER LIST SECTION

    function user_list(){
        $users = User::where('id', '!=', Auth::id())->get();
        $total_users = User::count();
        return view('admin.user.user_list',[
            'users'=>$users,
            'total_users'=>$total_users,
        ]);
    }

    function user_delete($user_id){
        User::find($user_id)->delete();
        return back()->with('success', 'User Deleted Successfully!');
    }
}
