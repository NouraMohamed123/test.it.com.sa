<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ComplaintController extends Controller
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
        $complaints = Complaint::with('company:id,name', 'EmployeeFrom:id,username', 'EmployeeAgainst:id,username')
            ->where('deleted_at', '=', null)->where('company_id',$employee->company->id)
            ->orderBy('id', 'desc')
            ->get();
        }elseif($user_auth->type == 2){
            $complaints = Complaint::with('company:id,name', 'EmployeeFrom:id,username', 'EmployeeAgainst:id,username')
            ->where('deleted_at', '=', null)->where('company_id',$user_auth->company->id)
            ->orderBy('id', 'desc')
            ->get();


        }
        else{
            $complaints = Complaint::with('company:id,name', 'EmployeeFrom:id,username', 'EmployeeAgainst:id,username')
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
        }
        return response()->json([
            'success' => true,
            'data' => $complaints,
        ]);
    }
    public function show($id)
    {
        $complaint = Complaint::with('company:id,name', 'EmployeeFrom:id,username', 'EmployeeAgainst:id,username')
            ->where('id', '=', $id)
            ->where('deleted_at', '=', null)
            ->first();

        if ($complaint) {
            return response()->json([
                'success' => true,
                'data' => $complaint
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found or has been deleted'
            ], 404);
        }
    }

    public function store(Request $request)
    {

        request()->validate([
            'title'        => 'required|string|max:255',
            'date'         => 'required',
            'reason'         => 'required',
            'company_id'  => 'required',
            'employee_from'  => 'required',
            'employee_against'  => 'required',
        ]);

        Complaint::create([
            'company_id'     => $request['company_id'],
            'employee_from'     => $request['employee_from'],
            'employee_against'     => $request['employee_against'],
            'title'        => $request['title'],
            'date'         => $request['date'],
            'time'         => $request['time'],
            'reason'  => $request['reason'],
            'description'  => $request['description'],
        ]);

        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        request()->validate([
            'title'        => 'required|string|max:255',
            'date'         => 'required',
            'reason'         => 'required',
            'company_id'  => 'required',
            'employee_from'  => 'required',
            'employee_against'  => 'required',
        ]);

        Complaint::whereId($id)->update([
            'company_id'     => $request['company_id'],
            'employee_from'     => $request['employee_from'],
            'employee_against'     => $request['employee_against'],
            'title'        => $request['title'],
            'date'         => $request['date'],
            'time'         => $request['time'],
            'reason'  => $request['reason'],
            'description'  => $request['description'],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Complaint::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
    }


    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {

        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $complaint_id) {
            Complaint::whereId($complaint_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
    }
}
