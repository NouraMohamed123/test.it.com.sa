<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Award;
use App\Models\Leave;
use App\Models\Travel;
use App\Models\Company;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Training;
use App\Models\Complaint;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfficeShift;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\EmployeeAccount;
use Illuminate\Validation\Rule;
use App\Models\EmployeeDocument;
use Illuminate\Auth\Access\Gate;
use App\Models\EmployeeExperience;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user_auth = Auth::guard('api')->user();
        if($user_auth->type == 3  ){
            $employee =  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
            $employees = Employee::with('company:id,name', 'office_shift:id,name', 'department:id,department', 'designation:id,designation')
                ->where('deleted_at', '=', null)
                ->where('company_id', '=', $employee->company->id)
                ->where('leaving_date', NULL)
                ->get();
        }elseif($user_auth->type == 2){

            $employees = Employee::with('company:id,name', 'office_shift:id,name', 'department:id,department', 'designation:id,designation')
            ->where('deleted_at', '=', null)
            ->where('company_id', '=', $user_auth->company->id)
            ->where('leaving_date', NULL)
            ->get();


        }
         else {
            $employees = Employee::with('company:id,name', 'office_shift:id,name', 'department:id,department', 'designation:id,designation')
                ->where('deleted_at', '=', null)
                ->where('leaving_date', NULL)
                ->get();
        }
        return response()->json(['success' => true, 'data' => $employees]);
    }
    public function show(Request $request, $id)
    {
        $employee = Employee::with('company:id,name', 'office_shift:id,name', 'department:id,department', 'designation:id,designation')
            ->where('id', $id)
            ->where('deleted_at', null)
            ->where('leaving_date', null)
            ->first();

        if ($employee) {
            return response()->json([
                'success' => true,
                'data' => $employee
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'employee not found'
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_auth = Auth::guard('api')->user();


        $this->validate($request, [
            'firstname'      => 'required|string|max:255',
            'avatar'      => 'required|max:255',
            'lastname'       => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'gender'         => 'required',
            'phone'          => 'required',
            'company_id'     => 'required',
            'department_id'  => 'required',
            'designation_id' => 'required',
            'office_shift_id'   => 'required',
            'role_users_id'   => 'required',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'fourth_name' => 'required|string',
            'start_trial_date' => 'required|date',
            'end_trial_date' => 'required|date',
            'job_description' => 'required|string',
            'language_level' => 'required|string',
            'specialization' => 'required|string',
            'educational_qualification' => 'required|string',
            'supervisor_name' => 'required|string|max:192',
            'social_enterprises_date' => 'required|date',
            'contract_expiry_date' => 'required|date',
            'medical_insurance_joining' => 'required|date',
            'medical_insurance_expiry' => 'required|date',
            'platform_contract_joining' => 'required|date',
            'platform_contract_expiry' => 'required|date',
            'date_of_birth_hijri' => 'required|date',
            'weekend_days' => 'required|string',
            'annual_leave_days' => 'required|string',
            'monthly_working_days' => 'required|string',
            'required_to_attend' => 'required|boolean',

            'marital_status' => 'required|string|in:single,married,divorced,widowed',

            'has_special_needs' => 'required|boolean',
            'disability_type' => 'nullable',
            'scene_image' => 'nullable',
            'job_type' => 'required|string',
            'contract_type' => 'required|string',
        ], [
            'email.unique' => 'This Email already taken.',
        ]);


        if ($request->file('avatar')) {
            $image = $request->file('avatar');
            $avatar = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/employees/avatar'),  $avatar);
        } else {
            $avatar = null;
        }
        if ($request->file('scene_image')) {
            $image = $request->file('scene_image');
            $scene_image = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/employees/scene_image'),  $scene_image);
        } else {
            $scene_image = null;
        }

        $data = [];
        $data['firstname'] = $request['firstname'];
        $data['lastname'] = $request['lastname'];
        $data['username'] = $request['firstname'] . ' ' . $request['lastname'];
        $data['country'] = $request['country'];
        $data['email'] = $request['email'];
        $data['gender'] = $request['gender'];
        $data['phone'] = $request['phone'];
        $data['address'] = $request['address'];
        $data['birth_date'] = $request['birth_date'];
        $data['company_id'] = $request['company_id'];
        $data['department_id'] = $request['department_id'];
        $data['designation_id'] = $request['designation_id'];
        $data['office_shift_id'] = $request['office_shift_id'];
        $data['joining_date'] = $request['joining_date'];
        $data['role_users_id'] = $request['role_users_id'];
        $data['fourth_name'] = $request['fourth_name'];
        $data['third_name'] = $request['third_name'];
        $data['start_trial_date'] = $request['start_trial_date'];
        $data['end_trial_date'] = $request['end_trial_date'];
        $data['job_description'] = $request['job_description'];
        $data['language_level'] = $request['language_level'];
        $data['specialization'] = $request['specialization'];
        $data['educational_qualification'] = $request['educational_qualification'];
        $data['supervisor_name'] = $request['supervisor_name'];
        $data['social_enterprises_date'] = $request['social_enterprises_date'];
        $data['contract_expiry_date'] = $request['contract_expiry_date'];
        $data['medical_insurance_joining'] = $request['medical_insurance_joining'];
        $data['medical_insurance_expiry'] = $request['medical_insurance_expiry'];
        $data['platform_contract_joining'] = $request['platform_contract_joining'];
        $data['platform_contract_expiry'] = $request['platform_contract_expiry'];
        $data['date_of_birth_hijri'] = $request['date_of_birth_hijri'];
        $data['weekend_days'] = $request['weekend_days'];
        $data['annual_leave_days'] = $request['annual_leave_days'];
        $data['monthly_working_days'] = $request['monthly_working_days'];
        $data['required_to_attend'] = $request['required_to_attend'];
        $data['marital_status'] = $request['marital_status'];
        $data['has_special_needs'] = $request['has_special_needs'];
        $data['disability_type'] = $request['disability_type'];
        $data['scene_image'] = $scene_image;
        $data['job_type'] = $request['job_type'];
        $data['contract_type'] = $request['contract_type'];

        $user_data = [];
        $user_data['username'] = $request['firstname'] . ' ' . $request['lastname'];
        $user_data['email'] = $request->email;
        $user_data['avatar'] = $avatar;
        $user_data['password'] = Hash::make($request['password']);
        $user_data['status'] = 1;
        $user_data['role_users_id'] = $request['role_users_id'];



        \DB::transaction(function () use ($request, $user_data, $data) {
            $user_data['type'] = 3;
            $user = User::create($user_data);
            $role = Role::findById($request['role_users_id']);
            $user->assignRole($role);

            // $data['id'] = $user->id;
            $data['user_id'] = $user->id;
            Employee::create($data);
        }, 10);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_edit')) {

        $this->validate($request, [
            'firstname'      => 'required|string|max:255',
            'lastname'       => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'gender'         => 'required',
            'phone'          => 'required',
            'total_leave'    => 'required|numeric|min:0',
            'company_id'     => 'required',
            'department_id'  => 'required',
            'designation_id' => 'required',
            'office_shift_id'   => 'required',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'email' => Rule::unique('users')->ignore($id),
            'role_users_id'   => 'required',
            'basic_salary'          => 'nullable|numeric',
            'hourly_rate'          => 'nullable|numeric',
            'fourth_name' => 'required|string',
            'start_trial_date' => 'required|date',
            'end_trial_date' => 'required|date',
            'job_description' => 'required|string',
            'language_level' => 'required|string',
            'specialization' => 'required|string',
            'educational_qualification' => 'required|string',
            'supervisor_name' => 'required|string|max:192',
            'social_enterprises_date' => 'required|date',
            'contract_expiry_date' => 'required|date',
            'medical_insurance_joining' => 'required|date',
            'medical_insurance_expiry' => 'required|date',
            'platform_contract_joining' => 'required|date',
            'platform_contract_expiry' => 'required|date',
            'date_of_birth_hijri' => 'required|date',
            'weekend_days' => 'required|string',
            'annual_leave_days' => 'required|string',
            'monthly_working_days' => 'required|string',
            'required_to_attend' => 'required|boolean',

            'marital_status' => 'required|string|in:single,married,divorced,widowed',

            'has_special_needs' => 'required|boolean',
            'disability_type' => 'nullable',
            'scene_image' => 'nullable',
            'job_type' => 'required|string',
            'contract_type' => 'required|string',
        ], [
            'email.unique' => 'This Email already taken.',
        ]);
        $employee = Employee::find($id);
        if ($request->file('avatar')) {
            $image = $request->file('avatar');
            $avatar = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/employees/avatar'),  $avatar);
        } else {
            $avatar =  $employee->avatar;
        }
        if ($request->file('scene_image')) {
            $image = $request->file('scene_image');
            $scene_image = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/employees/scene_image'),  $scene_image);
        } else {
            $scene_image =  $employee->scene_image;
        }



        $data = [];
        $data['firstname'] = $request['firstname'];
        $data['lastname'] = $request['lastname'];
        $data['username'] = $request['firstname'] . ' ' . $request['lastname'];
        $data['country'] = $request['country'];
        $data['email'] = $request['email'];
        $data['gender'] = $request['gender'];
        $data['phone'] = $request['phone'];
        $data['birth_date'] = $request['birth_date'];
        $data['company_id'] = $request['company_id'];
        $data['department_id'] = $request['department_id'];
        $data['designation_id'] = $request['designation_id'];
        $data['office_shift_id'] = $request['office_shift_id'];
        $data['joining_date'] = $request['joining_date'];
        $data['role_users_id'] = $request['role_users_id'];
        $data['leaving_date'] = $request['leaving_date'] ? $request['leaving_date'] : NULL;
        $data['marital_status'] = $request['marital_status'];
        $data['employment_type'] = $request['employment_type'];
        $data['city'] = $request['city'];
        $data['province'] = $request['province'];
        $data['zipcode'] = $request['zipcode'];
        $data['address'] = $request['address'];
        $data['basic_salary'] = $request['basic_salary'];
        $data['hourly_rate'] = $request['hourly_rate'];
        $data['fourth_name'] = $request['fourth_name'];
        $data['third_name'] = $request['third_name'];
        $data['start_trial_date'] = $request['start_trial_date'];
        $data['end_trial_date'] = $request['end_trial_date'];
        $data['job_description'] = $request['job_description'];
        $data['language_level'] = $request['language_level'];
        $data['specialization'] = $request['specialization'];
        $data['educational_qualification'] = $request['educational_qualification'];
        $data['supervisor_name'] = $request['supervisor_name'];
        $data['social_enterprises_date'] = $request['social_enterprises_date'];
        $data['contract_expiry_date'] = $request['contract_expiry_date'];
        $data['medical_insurance_joining'] = $request['medical_insurance_joining'];
        $data['medical_insurance_expiry'] = $request['medical_insurance_expiry'];
        $data['platform_contract_joining'] = $request['platform_contract_joining'];
        $data['platform_contract_expiry'] = $request['platform_contract_expiry'];
        $data['date_of_birth_hijri'] = $request['date_of_birth_hijri'];
        $data['weekend_days'] = $request['weekend_days'];
        $data['annual_leave_days'] = $request['annual_leave_days'];
        $data['monthly_working_days'] = $request['monthly_working_days'];
        $data['required_to_attend'] = $request['required_to_attend'];
        $data['marital_status'] = $request['marital_status'];
        $data['has_special_needs'] = $request['has_special_needs'];
        $data['disability_type'] = $request['disability_type'];
        $data['scene_image'] = $scene_image;
        //  $data['avatar'] = 'no_avatar.png' ;
        $data['job_type'] = $request['job_type'];
        $data['contract_type'] = $request['contract_type'];

        //calculation of total_leave & remaining_leave
        $employee_leave_info = Employee::find($id);
        if ($employee_leave_info->total_leave == 0) {
            $data['total_leave'] = $request->total_leave;
            $data['remaining_leave'] = $request->total_leave;
        } elseif ($request->total_leave > $employee_leave_info->total_leave) {
            $data['total_leave'] = $request->total_leave;
            $data['remaining_leave'] = $request->remaining_leave + ($request->total_leave - $employee_leave_info->total_leave);
        } elseif ($request->total_leave < $employee_leave_info->total_leave) {
            $data['total_leave'] = $request->total_leave;
            $data['remaining_leave'] = $request->remaining_leave - ($employee_leave_info->total_leave - $request->total_leave);
        } else {
            $data['total_leave'] = $request->total_leave;
            $data['remaining_leave'] = $employee_leave_info->remaining_leave;
        }

        $user_data = [];
        $user_data['username'] = $request['firstname'] . ' ' . $request['lastname'];
        $user_data['email'] = $request->email;
        // $user_data['password'] = Hash::make($request['password']);
        $user_data['role_users_id'] = $request['role_users_id'];

        \DB::transaction(function () use ($request, $id, $user_data, $data) {
            $user_data['type'] = 3;
            User::whereId($id)->update($user_data);
            $user = User::find($id);
            $data['user_id'] =  $user->id;
            Employee::find($id)->update($data);


            $user->syncRoles($data['role_users_id']);
        }, 10);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }



    public function update_social_profile(Request $request, $id)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_edit')) {

        Employee::whereId($id)->update($request->all());

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
        // if ($user_auth->can('employee_delete')) {

        Employee::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        User::whereId($id)->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_delete')) {
        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $employee_id) {
            Employee::whereId($employee_id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            User::whereId($employee_id)->update([
                'status' => 0,
            ]);
        }
        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }
    public function Get_all_employees()
    {
        $employees = Employee::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'username']);

        return response()->json($employees);
    }


    public function Get_employees_by_company(Request $request)
    {
        $employees = Employee::where('company_id', $request->id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'username']);

        return response()->json($employees);
    }


    public function Get_employees_by_department(Request $request)
    {

        $employees = Employee::where('department_id', $request->id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'username']);

        return response()->json($employees);
    }


    public function Get_office_shift_by_company(Request $request)
    {
        $office_shifts = OfficeShift::where('company_id', $request->id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);

        return response()->json($office_shifts);
    }

    public function import(Request $request)
{

        $request->validate([
            'employees' => 'required|array',
            'employees.*.firstname' => 'required|string|max:255',
            'employees.*.lastname' => 'required|string|max:255',
            'employees.*.country' => 'required|string|max:255',
            'employees.*.gender' => 'required',
            'employees.*.phone' => 'required',
            'employees.*.company_id' => 'required',
            'employees.*.department_id' => 'required',
            'employees.*.designation_id' => 'required',
            'employees.*.office_shift_id' => 'required',
            'employees.*.role_users_id' => 'required',
            'employees.*.email' => 'required|string|email|max:255|unique:users',
            'employees.*.password' => 'required|string|min:6|confirmed',
            'employees.*.password_confirmation' => 'required',
            'employees.*.fourth_name' => 'required|string',
            'employees.*.start_trial_date' => 'required|date',
            'employees.*.end_trial_date' => 'required|date',
            'employees.*.job_description' => 'required|string',
            'employees.*.language_level' => 'required|string',
            'employees.*.specialization' => 'required|string',
            'employees.*.educational_qualification' => 'required|string',
            'employees.*.supervisor_name' => 'required|string|max:192',
            'employees.*.social_enterprises_date' => 'required|date',
            'employees.*.contract_expiry_date' => 'required|date',
            'employees.*.medical_insurance_joining' => 'required|date',
            'employees.*.medical_insurance_expiry' => 'required|date',
            'employees.*.platform_contract_joining' => 'required|date',
            'employees.*.platform_contract_expiry' => 'required|date',
            'employees.*.date_of_birth_hijri' => 'required|date',
            'employees.*.weekend_days' => 'required|string',
            'employees.*.annual_leave_days' => 'required|string',
            'employees.*.monthly_working_days' => 'required|string',
            'employees.*.required_to_attend' => 'required|boolean',
            'employees.*.marital_status' => 'required|string|in:single,married,divorced,widowed',
            'employees.*.has_special_needs' => 'required|boolean',
            'employees.*.disability_type' => 'nullable|string',
            'employees.*.scene_image' => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            'employees.*.job_type' => 'required|string',
            'employees.*.contract_type' => 'required|string',
        ]);

        $createdEmployees = [];

        foreach ($request->employees as $employeeData) {
            $avatar = 'no_avatar.png';
            $user_data = [
                'username' => $employeeData['firstname'] . ' ' . $employeeData['lastname'],
                'email' => $employeeData['email'],
                'avatar' => $avatar,
                'password' => Hash::make($employeeData['password']),
                'status' => 1,
                'role_users_id' => $employeeData['role_users_id'],
                'type' => 3
            ];

            $data = \Illuminate\Support\Arr::except($employeeData, ['email', 'password', 'password_confirmation']);
            $data['username'] = $employeeData['firstname'] . ' ' . $employeeData['lastname'];
            $data['scene_image'] = 'no_avatar.png';

            DB::transaction(function () use ($user_data, $data) {
                $user = User::create($user_data);
                $role = Role::findById($data['role_users_id']);
                $user->assignRole($role);
                $data['user_id'] = $user->id;
                Employee::create($data);
            });
        }

        return response()->json(['success' => true]);


}

}
