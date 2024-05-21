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
use App\Models\Candidate;
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

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('employee_view')){
        $candidates = Candidate::with('jop')->where('deleted_at', '=', null)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json(['success' => true, 'data' => $candidates]);

        // }
        // return abort('403', __('You are not authorized'));

    }
    public function store(Request $request)
    {
        $user_auth = Auth::guard('api')->user();

        // if($user_auth->can('employee_add')){
        $this->validate($request, [
            'jop_id'     => 'required',
            'cv'         =>'nullable'

        ]);
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/candidates'), $filename);
        } else {
            $filename = null;
        }

        Candidate::create([
            'jop_id' => $request->jop_id,
            'cv' => $filename,
        ]);
        return response()->json(['success' => true]);
        // }
        //  return abort('403', __('You are not authorized'));
    }


    public function show($id)
    {
        $user_auth = Auth::guard('api')->user();
    }

    public function update(Request $request, $id)
    {
        $user_auth = Auth::guard('api')->user();
        // if($user_auth->can('employee_edit')){
        $candidate = Candidate::findOrFail($id);

        $this->validate($request, [
            'jop_id'     => 'required',
            'cv'         =>'nullable'
        ]);
        if ($request->hasFile('cv')) {
            $image = $request->file('cv');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/candidates'), $filename);
        } else {
            $filename = $candidate->cv;
        }
        $candidate->update([
            'jop_id' => $request->jop_id,
            'cv' => $filename,
        ]);
        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }


    public function destroy($id)
    {
        $user_auth = Auth::guard('api')->user();
        // if($user_auth->can('employee_delete')){

        Candidate::whereId($id)->update([
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
        // if($user_auth->can('employee_delete')){
        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $id) {
            Candidate::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }
}
