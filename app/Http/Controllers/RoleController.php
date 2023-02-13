<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Svg\Tag\Rect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    function add_permission(){
        $permissions = Permission::all();
        $roles = Role::all();
        $all_users = User::all();
        return view('admin.role.role',[
            'permissions'=>$permissions,
            'roles'=>$roles,
            'all_users'=>$all_users,
        ]);
    }

    function permisson_store(Request $request){
        Permission::create(['name' =>$request->permission_name]);
        return back();
    }

    function role_store(Request $request){
        $role = Role::create(['name' => $request->role_name]);
        $role->givePermissionTo($request->permission);
        return back();
    }

    function assign_role(Request $request){
        $user = User::find($request->user_id);
        $user->assignRole($request->role_id);
        return back();
    }

    function remove_role($user_id){
        $user = User::find($user_id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        return back();
    }

    // function edit_permission($role_id){
    //     Permission::fund
    // }

    function delete_permission($role_id){
        $role = Role::find($role_id);
        $role->syncPermissions([]);
        $role->delete();
        return back();
    }
}
