<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Trainer;
use App\Models\Employee;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Models\TrainingSkill;
use App\Models\EmployeeTraining;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $user_auth = Auth::guard('api')->user();
          if($user_auth->type == 3){
            $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
            $trainings = Training::where('deleted_at', '=', null)
            ->with('company:id,name','trainer:id,name','TrainingSkill:id,training_skill')
            ->join('employee_training', 'training.id', '=', 'employee_training.training_id')->where('employee_id', $employee->id)
            ->orderBy('id', 'desc')
            ->get();
            // if ($user_auth->can('training_view')){
        } else{
            $trainings = Training::where('deleted_at', '=', null)
            ->with('company:id,name','trainer:id,name','TrainingSkill:id,training_skill')
            ->orderBy('id', 'desc')
            ->get();
        }

            return response()->json(['success' => true, 'data' => $trainings]);

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
		// if ($user_auth->can('training_add')){

            $request->validate([
                'trainer_id'        => 'required',
                'assigned_to'       => 'required',
                'start_date'        => 'required',
                'end_date'          => 'required',
                'training_skill_id' => 'required',
                'status'            => 'required',
                'company_id'      => 'required',
            ]);

            $training = Training::create([
                'trainer_id'         => $request['trainer_id'],
                'start_date'         => $request['start_date'],
                'end_date'           => $request['end_date'],
                'company_id'         => $request['company_id'],
                'training_skill_id'  => $request['training_skill_id'],
                'training_cost'      => $request['training_cost'],
                'status'             => $request['status'],
                'description'        => $request['description'],
            ]);

            $training->assignedEmployees()->sync($request['assigned_to']);

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
        $training = Training::with('company','trainer','TrainingSkill')->where('deleted_at', '=', null)->findOrFail($id);
        return response()->json(['success' => true, 'data' => $training]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('training_edit')){

            $training = Training::where('deleted_at', '=', null)->findOrFail($id);
            $assigned_employees = EmployeeTraining::where('training_id', $id)->pluck('employee_id')->toArray();
            $companies = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);
            $employees = Employee::where('company_id' , $training->company_id)->where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','username']);
            $trainers = Trainer::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);
            $training_skills = TrainingSkill::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','training_skill']);

            return view('training.edit_training', compact('training','trainers','training_skills','companies','employees','assigned_employees'));

        // }
        // return abort('403', __('You are not authorized'));
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
          $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('training_edit')){

            $request->validate([
                'trainer_id'        => 'required',
                'assigned_to'       => 'required',
                'start_date'        => 'required',
                'end_date'          => 'required',
                'training_skill_id' => 'required',
                'status'            => 'required',
                'company_id'      => 'required',
            ]);

            Training::whereId($id)->update([
                'trainer_id'         => $request['trainer_id'],
                'start_date'         => $request['start_date'],
                'end_date'           => $request['end_date'],
                'company_id'         => $request['company_id'],
                'training_skill_id'  => $request['training_skill_id'],
                'training_cost'      => $request['training_cost'],
                'status'             => $request['status'],
                'description'        => $request['description'],
            ]);

            $training = Training::where('deleted_at', '=', null)->findOrFail($id);
            $training->assignedEmployees()->sync($request['assigned_to']);

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
		// if ($user_auth->can('training_delete')){

            Training::whereId($id)->update([
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
        // if($user_auth->can('training_delete')){
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $training_id) {
                Training::whereId($training_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
     }


    public function Get_all_trainers()
    {
        $trainers = Trainer::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);

        return response()->json($trainers);
    }
}
