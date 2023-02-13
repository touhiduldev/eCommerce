<?php

namespace App\Http\Controllers;

use App\Models\CustomerLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    function github_redirect(){
        return Socialite::driver('github')->redirect();
    }

    function github_callback(){
        $user = Socialite::driver('github')->user();
        if (CustomerLogin::where('email', $user->getEmail())->exists()) {

            if(Auth::guard('customerlogin')->attempt(['email'=>$user->getEmail(), 'password'=>'2544@#TaM'])){
                return redirect('/');
            }

        }else {

            CustomerLogin::insert([
                'name'=>$user->getName(),
                'email'=>$user->getEmail(),
                'password'=>bcrypt('2544@#TaM'),
                'created_at'=>Carbon::now(),
            ]);

            if(Auth::guard('customerlogin')->attempt(['email'=>$user->getEmail(), 'password'=>'2544@#TaM'])){
                return redirect('/');
            }

        }

    }
}
