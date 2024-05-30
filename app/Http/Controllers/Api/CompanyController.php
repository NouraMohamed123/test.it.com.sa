<?php

namespace App\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = Auth::guard('api')->user();

        if($user_auth->type == 2){
            $companies = Company::where('deleted_at', '=', null)->where('id', '=', $user_auth->company->id)
            ->orderBy('id', 'desc')
            ->get();

        }else{
            $companies = Company::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
        }

        return response()->json(['success' => true, 'data' => $companies]);



    }
    public function show($id)
    {
        $company = Company::where('id', $id)
            ->first();

        if ($company) {
            return response()->json([
                'success' => true,
                'data' => $company
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }
    }
    public function store(Request $request)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('company_add')) {
        request()->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
            'tax_number' => 'required',
            'job_classification' => 'nullable|max:255',
            'trade_register' => 'nullable|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'policy'=>'required',

        ]);

        if ($request->hasFile('logo')) {

            $image = $request->file('logo');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/logo'), $filename);
        } else {
            $filename =  'no_avatar.png';
        }
        if ($request->hasFile('tax_number_photo')) {

            $image = $request->file('tax_number_photo');
            $filename1 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/tax_number_photo'), $filename);
        } else {
            $filename1 =   'no_avatar.png';
        }
        if ($request->hasFile('job_classification')) {

            $image = $request->file('job_classification');
            $filename2 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/job_classification'), $filename);
        } else {
            $filename2 =   'no_avatar.png';
        }
        if ($request->hasFile('trade_register')) {

            $image = $request->file('trade_register');
            $filename3 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/trade_register'), $filename);
        } else {
            $filename3 =   'no_avatar.png';
        }

        Company::create([
            'name' => $request['name'],
            'logo' => $filename,
            'status' => $request['status'],
            'tax_number' => $request['tax_number'],
            'tax_number_photo' =>  $filename1,
            'job_classification' => $filename2,
            'trade_register' => $filename3,
            'email' => $request['email'],
            'phone' => $request['phone'],
            'attendance_time'=>$request['attendance_time'],
            'leave_time'=>$request['leave_time'],
            'policy'=>$request['policy'],

        ]);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }


    public function update(Request $request, $id)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('company_edit')) {
        request()->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
            'tax_number' => 'required|string|digits:10',
            'job_classification' => 'nullable|string|max:255',
            'trade_register' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'policy'=>'required',

        ]);

        $company = Company::findOrFail($id);

        if ($request->hasFile('logo')) {

            $image = $request->file('logo');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/logo'), $filename);
        } else {
            $filename =  $company->logo;
        }
        if ($request->hasFile('tax_number_photo')) {

            $image = $request->file('tax_number_photo');
            $filename1 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/tax_number_photo'), $filename);
        } else {
            $filename1 = $company->tax_number_photo;
        }
        if ($request->hasFile('job_classification')) {

            $image = $request->file('job_classification');
            $filename2 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/job_classification'), $filename);
        } else {
            $filename2 =  $company->job_classification;
        }
        if ($request->hasFile('trade_register')) {

            $image = $request->file('trade_register');
            $filename3 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/trade_register'), $filename);
        } else {
            $filename3 =  $company->trade_register;
        }

        $company->update([
            'name' => $request['name'],
            'logo' => $filename,
            'status' => $request['status'],
            'tax_number' => $request['tax_number'],
            'tax_number_photo' =>  $filename1,
            'job_classification' => $filename2,
            'trade_register' => $filename3,
            'email' => $request['email'],
            'phone' => $request['phone'],
            'attendance_time'=>$request['attendance_time'],
            'leave_time'=>$request['leave_time'],
            'policy'=>$request['policy'],


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
        // if ($user_auth->can('company_delete')){

        Company::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));

    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $user_auth = Auth::guard('api')->user();
        // if($user_auth->can('company_delete')){
        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $company_id) {
            Company::whereId($company_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }

    public function Get_all_Company()
    {
        $companies = Company::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get(['id', 'name']);

        return response()->json($companies);
    }
    public function QuickEntry(Request $request ,$id)
    {

        $user = User::where('company_id', $id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Not found user for this company',
            ], 401);
        }



        if (!$user) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
        $token = auth()->guard('api')->login($user);

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
    public function verification_attendance()
    {

        $user_auth = Auth::guard('api')->user();
        $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
        if (!empty($employee->company->attendance_time)) {
            $attendanceTime = Carbon::parse($employee->company->attendance_time);
            $currentTime = Carbon::now();
            if ($attendanceTime->diffInMinutes($currentTime, false) > 10) {
                return response()->json([
                    'message' => 'closed',
                ], 401);
            }
            return response()->json([
                'message' => 'open',
            ], 200);
        }
        return response()->json([
            'status' => 'open',
        ], 200);
    }
    public function verification_leave()
    {

        $user_auth = Auth::guard('api')->user();
        $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
        if (!empty($employee->company->leave_time)) {
            $attendanceTime = Carbon::parse($employee->company->leave_time);
            $currentTime = Carbon::now();
            if ($attendanceTime->diffInMinutes($currentTime, false) > 10) {
                return response()->json([
                    'status' => 'closed',
                ], 401);
            }
            return response()->json([
                'status' => 'open',
            ], 200);
        }
        return response()->json([
            'status' => 'open',
        ], 200);
    }
    public function import(Request $request)
{
    $user_auth = Auth::guard('api')->user();

    $request->validate([
        'companies' => 'required|array',
        'companies.*.name' => 'required|string|max:255',
        'companies.*.status' => 'required|in:active,inactive',
        'companies.*.tax_number' => 'required|string|max:255',
        'companies.*.email' => 'nullable|string|email|max:255',
        'companies.*.phone' => 'nullable|string|max:255',
        'companies.*.policy' => 'required|string|max:65535',
    ]);

    $createdCompanies = [];

    foreach ($request->companies as $companyData) {
        $filename = 'no_avatar.png';
        $filename1 = 'no_avatar.png';
        $filename2 = 'no_avatar.png';
        $filename3 = 'no_avatar.png';


        $company = Company::create([
            'name' => $companyData['name'],
            'logo' => $filename,
            'status' => $companyData['status'],
            'tax_number' => $companyData['tax_number'],
            'tax_number_photo' => $filename1,
            'job_classification' => $filename2,
            'trade_register' => $filename3,
            'email' => $companyData['email'],
            'phone' => $companyData['phone'],
            'attendance_time' => $companyData['attendance_time'] ?? null,
            'leave_time' => $companyData['leave_time'] ?? null,
            'policy' => $companyData['policy'],
        ]);

        $createdCompanies[] = $company;
    }

    return response()->json(['success' => true, 'data' => $createdCompanies]);

}
public function store_admin(Request $request)
{
     $user_auth = Auth::guard('api')->user();
    $request->validate([
        'username'  => 'required|string|max:255',
        'email'     => 'required|string|email|max:255|unique:users',
        'password'  => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required',
        'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
        'status'    => 'required',
        'role_id'=>'required|exists:roles,id',
        'company_id'    => 'required',
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
        'type' => 2,
        'company_id' => $request['company_id'],
    ]);
    $role = Role::findById($request['role_id']);
    $user->assignRole($role);

    return response()->json(['success' => true]);

}
}
