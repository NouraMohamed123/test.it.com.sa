<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Company;
use App\Models\Project;
use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\TaskDocument;
use Illuminate\Http\Request;
use App\Models\ScheduledTask;
use App\Models\TaskDiscussion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user_auth = Auth::guard('api')->user();
         $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();

            if($employee && $employee->type == 1){

            $count_not_started = Task::where('deleted_at', '=', null)->join('employee_task', 'tasks.id', '=', 'employee_task.task_id')
           ->where('employee_id', $employee->id)
            ->where('status', '=', 'not_started')
            ->count();
            $count_in_progress = Task::where('deleted_at', '=', null)->join('employee_task', 'tasks.id', '=', 'employee_task.task_id')
           ->where('employee_id', $employee->id)
            ->where('status', '=', 'progress')
            ->count();
            $count_cancelled = Task::where('deleted_at', '=', null)->join('employee_task', 'tasks.id', '=', 'employee_task.task_id')
           ->where('employee_id', $employee->id)
            ->where('status', '=', 'cancelled')
            ->count();
            $count_completed = Task::where('deleted_at', '=', null)->join('employee_task', 'tasks.id', '=', 'employee_task.task_id')
           ->where('employee_id', $employee->id)
            ->where('status', '=', 'completed')
            ->count();
            $tasks = Task::where('deleted_at', '=', null)->with('company:id,name','project:id,title')->join('employee_task', 'tasks.id', '=', 'employee_task.task_id')
            ->where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
            }else{
                $count_not_started = Task::where('deleted_at', '=', null)
                ->where('status', '=', 'not_started')
                ->count();
                $count_in_progress = Task::where('deleted_at', '=', null)
                ->where('status', '=', 'progress')
                ->count();
                $count_cancelled = Task::where('deleted_at', '=', null)
                ->where('status', '=', 'cancelled')
                ->count();
                $count_completed = Task::where('deleted_at', '=', null)
                ->where('status', '=', 'completed')
                ->count();

                $tasks = Task::where('deleted_at', '=', null)->with('company:id,name','project:id,title')->orderBy('id', 'desc')->get();
            }

           return response()->json(['success' => true, 'data' => $tasks,'count_not_started'=>$count_not_started,'count_in_progress'=>$count_in_progress,'count_cancelled'=>$count_cancelled,'count_completed'=>$count_completed]);




    }

    public function tasks_kanban()
    {
         $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('kanban_task')){

            $tasks_not_started = Task::where('deleted_at', '=', null)
            ->where('status', '=', 'not_started')
            ->with('project:id,title')->orderBy('id', 'desc')->get();
            $tasks_in_progress = Task::where('deleted_at', '=', null)
            ->where('status', '=', 'progress')
            ->with('project:id,title')->orderBy('id', 'desc')->get();
            $tasks_cancelled = Task::where('deleted_at', '=', null)
            ->where('status', '=', 'cancelled')
            ->with('project:id,title')->orderBy('id', 'desc')->get();
            $tasks_completed = Task::where('deleted_at', '=', null)
            ->where('status', '=', 'completed')
            ->with('project:id,title')->orderBy('id', 'desc')->get();
            $tasks_hold = Task::where('deleted_at', '=', null)
            ->where('status', '=', 'hold')
            ->with('project:id,title')->orderBy('id', 'desc')->get();
            return response()->json(['success' => true, 'tasks_not_started' => $tasks_not_started,'tasks_in_progress'=>$tasks_in_progress,'tasks_cancelled'=>$tasks_cancelled,'tasks_completed'=>$tasks_completed,'tasks_hold'=>$tasks_hold]);



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
		// if ($user_auth->can('task_add')){

            $request->validate([
                'title'           => 'required|string|max:255',
                'summary'         => 'required|string|max:255',
                'project_id'      => 'nullable',
                'start_date'      => 'required',
                'end_date'        => 'required',
                'status'          => 'required',
                'company_id'      => 'nullable',
                'priority'          => 'required',
            ]);

            $task = Task::create([
                'title'            => $request['title'],
                'summary'          => $request['summary'],
                'start_date'       => $request['start_date'],
                'end_date'         => $request['end_date'],
                'project_id'       => $request['project_id'],
                'company_id'       => $request['company_id'],
                'status'           => $request['status'],
                'priority'         => $request['priority'],
                'task_progress'    => $request['task_progress'],
                'description'      => $request['description'],
            ]);

            $task->assignedEmployees()->sync($request['assigned_to']);

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
        $task = Task::where('id', $id)
        ->where('deleted_at', null)
        ->first();
    if ($task) {
        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'task not found'
        ], 404);
    }
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
		// if ($user_auth->can('task_edit')){

            $request->validate([
                'title'           => 'required|string|max:255',
                'summary'         => 'required|string|max:255',
                'project_id'      => 'nullable',
                'start_date'      => 'required',
                'end_date'        => 'nullable',
                'status'          => 'required',
                'company_id'      => 'nullable',
                'priority'          => 'required',
            ]);

            Task::whereId($id)->update([
                'title'            => $request['title'],
                'summary'          => $request['summary'],
                'start_date'       => $request['start_date'],
                'end_date'         => $request['end_date'],
                'project_id'       => $request['project_id'],
                'company_id'       => $request['company_id'],
                'status'           => $request['status'],
                'priority'         => $request['priority'],
                'task_progress'    => $request['task_progress'],
                'description'      => $request['description'],
            ]);

            $task = Task::where('deleted_at', '=', null)->findOrFail($id);
            $task->assignedEmployees()->sync($request['assigned_to']);

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
		// if ($user_auth->can('task_delete')){

            Task::whereId($id)->update([
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
        //  if($user_auth->can('task_delete')){
             $selectedIds = $request->selectedIds;

             foreach ($selectedIds as $task_id) {
                Task::whereId($task_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
             }
             return response()->json(['success' => true]);
        //  }
        //  return abort('403', __('You are not authorized'));
      }


    //---------------------Task Details -----------------------------\\

    public function Create_task_discussions(Request $request)
    {


            $request->validate([
                'message'           => 'required|string',
            ]);

            TaskDiscussion::create([
                'message'            => $request['message'],
                'user_id'            => Auth::guard('api')->user()->id,
                'task_id'           => $request['task_id'],
            ]);

            return response()->json(['success' => true]);


    }

    public function destroy_task_discussion($id)
    {
         $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('task_details')){


            TaskDiscussion::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }

    public function Create_task_documents(Request $request)
    {
         $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('task_details')){

            $request->validate([
                'title'         => 'required|string|max:255',
                'attachment'    => 'required|file|mimes:pdf,docs,doc,pptx,jpeg,png,jpg,bmp,gif,svg|max:2048',

            ]);


            if ($request->hasFile('attachment')) {

                $image = $request->file('attachment');
                $attachment = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/tasks/documents'), $attachment);

            } else {
                $attachment = Null;
            }

            TaskDocument::create([
                'title'            => $request['title'],
                'task_id'          => $request['task_id'],
                'description'      => $request['description'],
                'attachment'       => $attachment,
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }


    public function destroy_task_documents($id)
    {
         $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('task_details')){

            TaskDocument::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }


    public function update_task_status(Request $request, $id)
    {
         $user_auth = Auth::guard('api')->user();
		// if ($user_auth->can('employee_edit')){

            $request->validate([
                'status'          => 'required',
            ]);

            Task::whereId($id)->update([
                'status'           => $request['status'],
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }
     public function repeating(Request $request){
        $validatedData = $request->validate([
            'task_id' => 'required|integer',
            'repeat_type' => 'required|string',
        ]);

        ScheduledTask::updateOrCreate(
            [
                'task_id' => $validatedData['task_id']
            ],
            [
                'repeat_type' => $validatedData['repeat_type']

            ]
        );

        return response()->json(['message' => 'Scheduled task created or updated successfully.']);



    }
}
