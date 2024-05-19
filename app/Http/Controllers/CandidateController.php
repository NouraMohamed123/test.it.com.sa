<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Jop;
use App\Models\Task;
use App\Models\User;
use App\Models\Award;
use App\Models\Candidate;
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
        $user_auth = auth()->user();
		if ($user_auth->can('employee_view')){
            $candidates = Candidate::all();

            return view('candidates.candidates_list', compact('candidates'));
        }
        return abort('403', __('You are not authorized'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_auth = auth()->user();
        if($user_auth->can('employee_add')){
            $jops = Jop::all();

            return view('candidates.create_candidates', compact('jops'));
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_auth = auth()->user();

        if($user_auth->can('employee_add')){
            $this->validate($request, [
                'jop_id'     => 'required',
            ]);
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $filename = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/avatar'), $filename);

            } else {
                $filename = null;
            }

            Candidate::create([
                'jop_id' => $request->jop_id,
                'cv' => $filename,
            ]);
            return response()->json(['success' => true]);
        }
         return abort('403', __('You are not authorized'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_auth = auth()->user();
        if($user_auth->can('employee_details')){

            $employee = Employee::where('deleted_at', '=', null)->findOrFail($id);
            $experiences = EmployeeExperience::where('employee_id' , $id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
            $documents = EmployeeDocument::where('employee_id' , $id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
            $accounts_bank = EmployeeAccount::where('employee_id' , $id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
            $companies = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);
            $office_shifts = OfficeShift::where('company_id' , $employee->company_id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);
            $departments = Department::where('company_id' , $employee->company_id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','department']);
            $designations = Designation::where('department_id' , $employee->department_id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','designation']);
            $roles = Role::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);

            $leaves = Leave::where('employee_id' , $id)
            ->join('companies','companies.id','=','leaves.company_id')
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

            $awards = Award::where('employee_id' , $id)
            ->join('companies','companies.id','=','awards.company_id')
            ->join('departments','departments.id','=','awards.department_id')
            ->join('employees','employees.id','=','awards.employee_id')
            ->join('award_types','award_types.id','=','awards.award_type_id')
            ->where('awards.deleted_at' , '=', null)
            ->select('awards.*',
            'employees.username AS employee_name', 'employees.id AS employee_id',
            'award_types.title AS award_type_title', 'award_types.id AS award_type_id',
            'companies.name AS company_name', 'companies.id AS company_id',
            'departments.department AS department_name', 'departments.id AS department_id')
            ->orderBy('id', 'desc')
            ->get();

            $travels = Travel::where('employee_id' , $id)
            ->with('company:id,name','employee:id,username','arrangement_type:id,title')
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();

            $complaints = Complaint::where('employee_from' , $id)
            ->with('company:id,name','EmployeeFrom:id,username','EmployeeAgainst:id,username')
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();

            $tasks = Task::where('deleted_at', '=', null)
            ->with('company:id,name','project:id,title','assignedEmployees')
            ->join('employee_task','tasks.id','=','employee_task.task_id')
            ->where('employee_id', $id)
            ->orderBy('id', 'desc')
            ->get();

            $projects = Project::where('deleted_at', '=', null)
            ->with('company:id,name','client:id,username','assignedEmployees')
            ->join('employee_project','projects.id','=','employee_project.project_id')
            ->where('employee_id', $id)
            ->orderBy('id', 'desc')
            ->get();

            $trainings = Training::where('deleted_at', '=', null)
            ->with('company:id,name','trainer:id,name','TrainingSkill:id,training_skill','assignedEmployees')
            ->join('employee_training','trainings.id','=','employee_training.training_id')
            ->where('employee_id', $id)
            ->orderBy('id', 'desc')
            ->get();

            return view('jops.jops_details',
                compact('employee','companies','departments','designations','roles','documents',
                            'office_shifts','experiences','accounts_bank','leaves','awards','travels','complaints',
                            'tasks','projects','trainings')
                        );
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_auth = auth()->user();
        if($user_auth->can('employee_edit')){
            $candidate = Candidate::findOrFail($id);
            $jops = Jop::all();
            return view('candidates.edit_candidates', compact('candidate','jops'));
        }
        return abort('403', __('You are not authorized'));
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
        $user_auth = auth()->user();
        if($user_auth->can('employee_edit')){
            $candidate = Candidate::findOrFail($id);

            $this->validate($request, [
                'jop_id'     => 'required',
            ]);
            if ($request->hasFile('cv')) {
                $image = $request->file('cv');
                $filename = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/avatar'), $filename);

            } else {
                $filename = $candidate->cv;
            }
            $candidate->update([
                'jop_id' => $request->jop_id,
                'cv' => $filename,
            ]);
            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_auth = auth()->user();
        if($user_auth->can('employee_delete')){

            Candidate::whereId($id)->delete();

            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }

     //-------------- Delete by selection  ---------------\\

     public function delete_by_selection(Request $request)
     {
        $user_auth = auth()->user();
        if($user_auth->can('employee_delete')){
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $employee_id) {
                Candidate::whereId($employee_id)->delete();
            }
            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
     }




}
