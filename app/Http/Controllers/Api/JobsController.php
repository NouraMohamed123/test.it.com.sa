<?php

namespace App\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use App\Models\Jop;
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
use Illuminate\Http\Request;

use App\Models\EmployeeAccount;
use Illuminate\Validation\Rule;
use App\Models\EmployeeDocument;
use Illuminate\Auth\Access\Gate;
use App\Models\EmployeeExperience;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_view')) {
            $jops = Jop::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
            return response()->json(['success' => true,'jops' => $jops]);

        // }
        // return abort('403', __('You are not authorized'));
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
        // if ($user_auth->can('employee_add')) {

            $this->validate($request, [
                'job_title'      => 'required|string|max:255',
                'required_candidates'   => 'required',
                'work_area'         => 'required',
                'city'          => 'required',
                'academic_qualification'     => 'required',
                'language_level'  => 'required',
                'nationality' => 'required',
                'disabilities_allowed'   => 'required',
                'required_age'   => 'required',
                'work_type'     => 'required',
                'gender'  => 'required',
                'working_hours' => 'required',
                'basic_salary' => 'required',
                'monthly_attendance_days' => 'required',
                'weekly_rest_days' => 'required',

            ]);

            Jop::create($request->all());
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
    public function show($id)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_details')) {

            $jop = Jop::findOrFail($id);
            return response()->json(['success' => true,'data' => $jop]);

        // }
        // return abort('403', __('You are not authorized'));
    }

    /**
     * Show the form for editing the specified resource.
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
                'job_title'      => 'required|string|max:255',
                'required_candidates'   => 'required',
                'work_area'         => 'required',
                'city'          => 'required',
                'academic_qualification'     => 'required',
                'language_level'  => 'required',
                'nationality' => 'required',
                'disabilities_allowed'   => 'required',
                'required_age'   => 'required',
                'work_type'     => 'required',
                'gender'  => 'required',
                'working_hours' => 'required',
                'basic_salary' => 'required',
                'monthly_attendance_days' => 'required',
                'weekly_rest_days' => 'required',

            ]);
            $jop = Jop::find($id);

            $jop->update($request->all());

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

            Jop::whereId($id)->update([
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
        // if ($user_auth->can('employee_delete')) {
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $employee_id) {
                Jop::whereId($employee_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);


            }
            return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }
}
