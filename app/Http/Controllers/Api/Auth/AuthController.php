<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\AppUsers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

     public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = User::where('id', Auth::guard('api')->user()->id)->first();
        $role = Role::with('permissions')->where('id', $user->role_users_id)->first();

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $permissions = $role->permissions->pluck('name');


        return response()->json([
            'access_token' => $token,
            "data" => $user,
            "roles" => $role,
            'permissions' => $permissions,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

  /**
   * Register a User.
   *
   * @return \Illuminate\Http\JsonResponse
   */

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => "009665" . $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token,
            "data" => $user,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /**
     * Log out the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('api')->logout();
        return response()->json(['message' => 'عملية تسجيل الخروج تمت بنجاح']);
   }
}
