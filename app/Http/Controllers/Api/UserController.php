<?php

namespace App\Http\Controllers\Api;

use File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {

        $user = User::with('RoleUser.permissions')->where('id', Auth::guard('api')->user()->id)->first();

        return response()->json([

            'user' => $user,

        ]);
    }
    public function index()
    {
           $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('user_view')){

            $roles = Role::where('guard_name', 'api')->where('deleted_at', '=', null)->get(['id','name']);
            $users = User::with('RoleUser.permissions')->orderBy('id', 'desc')->get();

            return response()->json(['success' => true,'roles' => $roles,'users'=>$users]);


        // }
        // return abort('403', __('You are not authorized'));

    }

    public function store(Request $request)
    {
           $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('user_add')){

            $request->validate([
                'username'  => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
                'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
                'status'    => 'required',
                'role_id'=>'required',
            ]);

            if ($request->hasFile('avatar')) {


                $image = $request->file('avatar');
                $filename = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/users'), $filename);

            } else {
                $filename = 'no_avatar.png';
            }

            $user = User::create([
                'username'  => $request['username'],
                'email'     => $request['email'],
                'avatar'    => $filename,
                'password'  => Hash::make($request['password']),
                'role_users_id'   => $request['role_id'],
                'status'    => $request['status'],
            ]);

            $user->assignRole($request['role_id']);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }


    public function update(Request $request, $id)
    {
           $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('user_edit')){

            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users',
                'email' => Rule::unique('users')->ignore($id),
                'username'  => 'required|string|max:255',
                'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
                'password'  =>  'sometimes|nullable|string|confirmed|min:6,'.$id,
                'status'    => 'required',

            ], [
                'email.unique' => 'This Email already taken.',
            ]);

            $user = User::findOrFail($id);
            $current = $user->password;

            if ($request->password != null) {
                if ($request->password != $current) {
                    $pass = Hash::make($request->password);
                } else {
                    $pass = $user->password;
                }
            } else {
                $pass = $user->password;
            }

            $currentAvatar = $user->avatar;
            if ($request->avatar != null) {
                if ($request->avatar != $currentAvatar) {

                    $image = $request->file('avatar');
                    $filename = time().'.'.$image->extension();
                    $image->move(public_path('/assets/images/users'), $filename);
                    $path = public_path() . '/assets/images/users';
                    $userPhoto = $path . '/' . $currentAvatar;
                    if (file_exists($userPhoto)) {
                        if ($user->avatar != 'no_avatar.png') {
                            @unlink($userPhoto);
                        }
                    }
                } else {
                    $filename = $currentAvatar;
                }
            }else{
                $filename = $currentAvatar;
            }


            $user = User::whereId($id)->update([
                'username'  => $request['username'],
                'email'     => $request['email'],
                'avatar'    => $filename,
                'password'  => $pass,
                'status'    => $request['status'],
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('user_delete')){

            User::whereId($id)->update([
                'status' => 0,
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }


    public function assignRole(Request $request)
    {
           $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('group_permission') && $user_auth->role_users_id == 1 && $request->role_id != 3){

            //remove role
            $get_user = User::find($request->user_id);
            $get_user->removeRole($get_user->role_users_id);

            User::whereId($request->user_id)->update([
                'role_users_id' => $request->role_id,
            ]);

            $user_updated = User::find($request->user_id);
            $user_updated->assignRole($request->role_id);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }





     // Factory data
    //  public function getAllPermissions()
    //  {
    //         $user_auth = Auth::guard('api')->user();
    //      $user_auth->assignRole(1);

    //      $all_permissions  = Permission::pluck('name');
    //      $role = Role::find(1);
    //      $role->syncPermissions($all_permissions);
    //  }

}
