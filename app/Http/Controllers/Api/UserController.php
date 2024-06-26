<?php

namespace App\Http\Controllers\Api;

use File;
use App\Models\User;
use App\Models\Employee;
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

        $user = User::where('id', Auth::guard('api')->user()->id)->first();
        $role = Role::with('permissions')->where('id', $user->role_users_id)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $permissions = $role->permissions->pluck('name');
        return response()->json([

            'data' => $user,
            "roles" => $role,
            'permissions' => $permissions,

        ]);
    }
    public function showPrivcy(Request $request)
    {

        $user_auth = Auth::guard('api')->user();
        $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
        if($employee && $employee->type == 3){
           $policy  =  $employee->company->policy;
        }else{
            return response()->json([
                'message' => 'user not employee',
            ]);
        }
        return response()->json([
            'data' => $policy,
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
                'role_id'=>'required|exists:roles,id',
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
            $role = Role::findById($request['role_id']);
            $user->assignRole($role);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }

    public function show($id)
    {
        $user = User::with('RoleUser.permissions')
            ->where('id', '=', $id)
            ->where('deleted_at', '=', null)
            ->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user not found or has been deleted'
            ], 404);
        }
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
            //remove role
            $get_user = User::find($request->user_id);
            $get_user->removeRole($get_user->role_users_id);

            User::whereId($id)->update([
                'role_users_id' => $request->role_id,
            ]);
            $user->assignRole($request->role_id);
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


            //remove role
            $get_user = User::find($request->user_id);
            $get_user->removeRole($get_user->role_users_id);

            User::whereId($request->user_id)->update([
                'role_users_id' => $request->role_id,
            ]);

            $user_updated = User::find($request->user_id);
            $user_updated->assignRole($request->role_id);

            return response()->json(['success' => true]);


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

    public function import(Request $request)
{
    $user_auth = Auth::guard('api')->user();


    $request->validate([
        'users' => 'required|array',
        'users.*.username'  => 'required|string|max:255',
        'users.*.email'     => 'required|string|email|max:255|unique:users',
        'users.*.password'  => 'required|string|min:6|confirmed',
        'users.*.password_confirmation' => 'required',
        'users.*.avatar'    => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
        'users.*.status'    => 'required',
        'users.*.role_id' => 'required|exists:roles,id',
    ]);

    $createdUsers = [];

    foreach ($request->users as $userData) {


        $user = User::create([
            'username'  => $userData['username'],
            'email'     => $userData['email'],
            'avatar'    => 'no_avatar.png',
            'password'  => Hash::make($userData['password']),
            'role_users_id' => $userData['role_id'],
            'status'    => $userData['status'],
        ]);

        $role = Role::findById($userData['role_id']);
        $user->assignRole($role);

        $createdUsers[] = $user;
    }

    return response()->json(['success' => true, 'data' => $createdUsers]);

}

}
