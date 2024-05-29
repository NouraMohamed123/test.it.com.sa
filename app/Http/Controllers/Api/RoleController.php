<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->where('guard_name','api')->get(['id','name']);
        return response()->json(['success' => true,'data' => $roles]);
    }




    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'permission' => 'required',
    //     ]);
    //     $role = Role::create(['name' => $request->input('name')]);
    //     $role->syncPermissions($request->input('permission'));
    //     return response()->json([
    //         'message' => 'true',
    //         'data' => $role,

    //     ], 200);
    // // }
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'permissions' => 'required',
    //     ]);

    //     $role = Role::create(['name' => $request->input('name')]);
    //     $role->syncPermissions($request->input('permissions'));
    //     return response()->json([
    //         'message' => 'true',
    //         'data' => $role,

    //     ]);
    // }
    public function store(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,name', // Ensure each permission exists
    ]);

    // Check if the validation fails
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Create the new role
    $role = Role::create(['name' => $request->input('name')]);

    // Sync the permissions with the role
    $role->syncPermissions($request->input('permissions'));

    // Return a successful response
    return response()->json([
        'message' => 'Role created successfully',
        'data' => $role,
    ]);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Role $role)
    // {
    //     $role =  Role::with('permissions')->where('id', $role->id)->first();

    //     return response()->json($role);


    // }
    public function show(Role $role)
    {
        // Fetch the role with its permissions
        $role = Role::with('permissions')->where('id', $role->id)->first();

        // Extract permissions from the role
        $permissions = $role->permissions->pluck('name');

        // Return the role ID, name, and permissions as JSON
        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $permissions->toArray(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required',
        ]);


        $role->syncPermissions($request->input('permissions'));
        $role->update(['name' => $request->name]);

        return response()->json([
            'data' => $role,
            'message' => 'Role update successfully',
        ], 200);
    }


    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'message' => 'Role deleted successfully',
        ], 200);
    }
}
