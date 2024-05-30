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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = Role::create(['name' => $request->input('name')]);
        $syncedPermissions = $role->syncPermissions($request->input('permissions'));
        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role,
        ]);
    }



    public function show($id)
    {

        $role = Role::with('permissions')->where('id', $id)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $permissions = $role->permissions->pluck('name');


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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = Role::with('permissions')->where('id', $id)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->update(['name' => $request->input('name')]);

        // Sync the role's permissions
        $role->syncPermissions($request->input('permissions'));

        // Return the updated role with its permissions
        return response()->json([
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ],
            'message' => 'Role updated successfully',
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
