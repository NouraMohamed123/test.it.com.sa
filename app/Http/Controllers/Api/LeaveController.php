<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LeaveController extends Controller
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
            $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
            $leaves = Leave::
            join('companies','companies.id','=','leaves.company_id')
            ->join('departments','departments.id','=','leaves.department_id')
            ->join('employees','employees.id','=','leaves.employee_id')
            ->join('leave_types','leave_types.id','=','leaves.leave_type_id')
            ->where('leaves.deleted_at' , '=', null)->where('employee_id',$employee->id)
            ->select('leaves.*',
            'employees.username AS employee_name', 'employees.id AS employee_id',
            'leave_types.title AS leave_type_title', 'leave_types.id AS leave_type_id',
            'companies.name AS company_name', 'companies.id AS company_id',
            'departments.department AS department_name', 'departments.id AS department_id')
            ->orderBy('id', 'desc')
            ->get();
            } elseif($user_auth->type == 2){
                $leaves = Leave::
            join('companies','companies.id','=','leaves.company_id')
            ->join('departments','departments.id','=','leaves.department_id')
            ->join('employees','employees.id','=','leaves.employee_id')
            ->join('leave_types','leave_types.id','=','leaves.leave_type_id')
            ->where('leaves.deleted_at' , '=', null)->where('company_id', $user_auth->company->id)
            ->select('leaves.*',
            'employees.username AS employee_name', 'employees.id AS employee_id',
            'leave_types.title AS leave_type_title', 'leave_types.id AS leave_type_id',
            'companies.name AS company_name', 'companies.id AS company_id',
            'departments.department AS department_name', 'departments.id AS department_id')
            ->orderBy('id', 'desc')
            ->get();

            }
            else{
                $leaves = Leave::
                join('companies','companies.id','=','leaves.company_id')
                ->join('departments','departments.id','=','leaves.department_id')
                ->join('employees','employees.id','=','leaves.employee_id')
                ->join('leave_types','leave_types.id','=','leaves.leave_type_id')
                ->where('leaves.deleted_at' , '=', null)
                ->select('leaves.*',
                'employees.username AS employee_name', 'employees.id AS employee_id',
                'leave_types.title AS leave_type_title', 'leave_types.id AS leave_type_id',
                'companies.name AS company_name', 'companies.id AS company_id',
                'departments.department AS department_name', 'departments.id AS department_id')
                ->orderBy('id', 'desc')
                ->get();
            }
            return response()->json(['success' => true, 'data' => $leaves]);


        // }
        // return abort('403', __('You are not authorized'));
    }
    public function show(Request $request, $id)
    {
        $leave = Leave::
            where('id', $id)
            ->whereNull('deleted_at')
            ->first();

            if ($leave) {
                return response()->json([
                    'success' => true,
                    'data' => $leave
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'leave not found'
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


            request()->validate([
                'employee_id'      => 'required',
                'company_id'      => 'required',
                'department_id'      => 'required',
                'leave_type_id'    => 'required',
                'start_date'       => 'required',
                'end_date'         => 'required|after_or_equal:start_date',
                'status'           => 'required',
                'attachment'      => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);

            if ($request->hasFile('attachment')) {


                $image = $request->file('attachment');
                $filename = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/leaves'), $filename);

            } else {
                $filename = 'no_image.png';
            }

            $start_date = new DateTime($request->start_date);
            $end_date = new DateTime($request->end_date);
            $day     = $start_date->diff($end_date);
            $days_diff    = $day->d +1;
            $leave_type = LeaveType::findOrFail($request['leave_type_id']);

            $leave_data= [];
            $leave_data['employee_id'] = $request['employee_id'];
            $leave_data['company_id'] = $request['company_id'];
            $leave_data['department_id'] = $request['department_id'];
            $leave_data['leave_type_id'] = $request['leave_type_id'];
            $leave_data['start_date'] = $request['start_date'];
            $leave_data['end_date'] = $request['end_date'];
            $leave_data['days'] = $days_diff;
            $leave_data['reason'] = $request['reason'];
            $leave_data['attachment'] = $filename;
            $leave_data['half_day'] = $request['half_day'];
            $leave_data['status'] = $request['status'];

            $employee_leave_info = Employee::find($request->employee_id);

            if($days_diff > $employee_leave_info->remaining_leave)
            {
                return response()->json(['remaining_leave' => "remaining leaves are insufficient", 'isvalid' => false]);
            }
            elseif($request->status == 'approved'){
                $employee_leave_info->remaining_leave = $employee_leave_info->remaining_leave - $days_diff;
                $employee_leave_info->update();
            }

            Leave::create($leave_data);

            return response()->json(['success' => true ,'isvalid' => true]);


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
		// if ($user_auth->can('leave_edit')){

            request()->validate([
                'company_id'      => 'required',
                'department_id'      => 'required',
                'employee_id'      => 'required',
                'leave_type_id'    => 'required',
                'start_date'       => 'required',
                'end_date'         => 'required',
                'status'           => 'required',
                'attachment'      => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);

            $leave = Leave::findOrFail($id);

            $CurrentAttachement = $leave->attachment;
            if ($request->attachment != null) {
                if ($request->attachment != $CurrentAttachement) {

                    $image = $request->file('attachment');
                    $filename = time().'.'.$image->extension();
                    $image->move(public_path('/assets/images/leaves'), $filename);
                    $path = public_path() . '/assets/images/leaves';
                    $LeavePhoto = $path . '/' . $CurrentAttachement;
                    if (file_exists($LeavePhoto)) {
                        if ($leave->attachment != 'no_image.png') {
                            @unlink($LeavePhoto);
                        }
                    }
                } else {
                    $filename = $CurrentAttachement;
                }
            }else{
                $filename = $CurrentAttachement;
            }

            $start_date = new DateTime($request->start_date);
            $end_date = new DateTime($request->end_date);
            $day     = $start_date->diff($end_date);
            $days_diff    = $day->d +1;
            $leave_type = LeaveType::findOrFail($request['leave_type_id']);

            $leave_data= [];
            $leave_data['employee_id'] = $request['employee_id'];
            $leave_data['company_id'] = $request['company_id'];
            $leave_data['department_id'] = $request['department_id'];
            $leave_data['leave_type_id'] = $request['leave_type_id'];
            $leave_data['start_date'] = $request['start_date'];
            $leave_data['end_date'] = $request['end_date'];
            $leave_data['days'] = $days_diff;
            $leave_data['reason'] = $request['reason'];
            $leave_data['attachment'] = $filename;
            $leave_data['half_day'] = $request['half_day'];
            $leave_data['status'] = $request['status'];


            // return the old remaining_leave
            if($leave->status == 'approved'){

                $employee_leave_info = Employee::find($request->employee_id);
                if($days_diff > ($employee_leave_info->remaining_leave + $leave->days))
                {
                    return response()->json(['remaining_leave' => "remaining leaves are insufficient", 'isvalid' => false]);
                }else{
                    $employee_leave_info->remaining_leave = $employee_leave_info->remaining_leave + $leave->days;
                    $employee_leave_info->update();
                }

            }


            if($leave->status != 'approved'){
                $employee_leave_info = Employee::find($request->employee_id);
                if($days_diff > $employee_leave_info->remaining_leave)
                {
                    return response()->json(['remaining_leave' => "remaining leaves are insufficient", 'isvalid' => false]);
                }
            }

            if($request->status == 'approved'){
                $employee_leave_info = Employee::find($request->employee_id);
                $employee_leave_info->remaining_leave = $employee_leave_info->remaining_leave - $days_diff;
                $employee_leave_info->update();
            }


            Leave::find($id)->update($leave_data);

            return response()->json(['success' => true ,'isvalid' => true]);

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
		// if ($user_auth->can('leave_delete')){

            Leave::whereId($id)->update([
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
        // if($user_auth->can('leave_delete')){
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $leave_id) {
                Leave::whereId($leave_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
     }
}
