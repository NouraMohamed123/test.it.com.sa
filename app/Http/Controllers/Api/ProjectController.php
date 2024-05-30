<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectDiscussion;
use App\Models\ProjectDocument;
use App\Models\ProjectIssue;
use App\Models\EmployeeProject;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ProjectController extends Controller
{
    public function index()
    {
        $user_auth = Auth::guard('api')->user();
        $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
        if($employee && $employee->type == 3){
            $count_not_started = Project::whereNull('deleted_at')
            ->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', $employee->id)
            ->where('status', 'not_started')
            ->count();

            $count_in_progress = Project::whereNull('deleted_at')->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', $employee->id)
                ->where('status', 'progress')
                ->count();
            $count_cancelled = Project::whereNull('deleted_at')->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', $employee->id)
                ->where('status', 'cancelled')
                ->count();
            $count_completed = Project::whereNull('deleted_at')->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', $employee->id)
                ->where('status', 'completed')
                ->count();

            $projects = Project::with('company','assignedEmployees')->whereNull('deleted_at')->join('employee_project', 'projects.id', '=', 'employee_project.project_id')
            ->where('employee_id', $employee->id)

                ->orderBy('id', 'desc')
                ->get();

        }else{

            $count_not_started = Project::whereNull('deleted_at')
            ->where('status', 'not_started')
            ->count();

            $count_in_progress = Project::whereNull('deleted_at')
                ->where('status', 'progress')
                ->count();
            $count_cancelled = Project::whereNull('deleted_at')
                ->where('status', 'cancelled')
                ->count();
            $count_completed = Project::whereNull('deleted_at')
                ->where('status', 'completed')
                ->count();

            $projects = Project::with('company','assignedEmployees')->whereNull('deleted_at')

                ->orderBy('id', 'desc')
                ->get();

        }

        return response()->json([
            'data' => $projects,
            'count_not_started' => $count_not_started,
            'count_in_progress' => $count_in_progress,
            'count_cancelled' => $count_cancelled,
            'count_completed' => $count_completed,
        ]);

        return response()->json(['error' => __('حدث خطاء في عرض الداتا')], 403);
    }
    public function store(Request $request)
    {
        $user_auth = Auth::guard('api')->user();
        $request->validate([
            'title'           => 'required|string|max:255',
            'summary'         => 'required|string|max:255',
            'company_id'      => 'nullable',
            'assigned_to'     => 'required',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'priority'        => 'required',
            'status'          => 'required',
        ]);
        if($user_auth->type = 2){
            $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
            $request['company_id'] =  $employee->company->id;
         }
        $project  = Project::create([
            'title'            => $request['title'],
            'summary'          => $request['summary'],
            'start_date'       => $request['start_date'],
            'end_date'         => $request['end_date'],
            'company_id'       => $request['company_id'],
            'priority'         => $request['priority'],
            'status'           => $request['status'],
            'project_progress' => $request['project_progress'],
            'description'      => $request['description'],
        ]);

        $project->assignedEmployees()->sync($request['assigned_to']);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }
    public function show($id)
    {

        $project = Project::where('deleted_at', '=', null)->findOrFail($id);
        $discussions = ProjectDiscussion::where('project_id', $id)
            ->where('deleted_at', '=', null)
            ->with('User:id,username')
            ->orderBy('id', 'desc')
            ->get();

        $issues = ProjectIssue::where('project_id', $id)
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();

        $documents = ProjectDocument::where('project_id', $id)
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();

        $tasks = Task::where('project_id', $id)
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();

        $companies = Company::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $project,
            'tasks' => $tasks,
            'companies' => $companies,
            'discussions' => $discussions,
            'issues' => $issues,
            'documents' => $documents,



        ]);
    }
    public function update(Request $request, $id)
    {


        $request->validate([
            'title'           => 'required|string|max:255',
            'summary'         => 'required|string|max:255',
            'company_id'      => 'required',
            'assigned_to'     => 'required',
            'start_date'      => 'required',
            'end_date'        => 'nullable',
            'priority'        => 'required',
            'status'          => 'required',
        ]);

        Project::whereId($id)->update([
            'title'            => $request['title'],
            'summary'          => $request['summary'],
            'start_date'       => $request['start_date'],
            'end_date'         => $request['end_date'],
            'company_id'       => $request['company_id'],
            'priority'         => $request['priority'],
            'status'           => $request['status'],
            'project_progress' => $request['project_progress'],
            'description'      => $request['description'],
        ]);

        $project = Project::where('deleted_at', '=', null)->findOrFail($id);
        $project->assignedEmployees()->sync($request['assigned_to']);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }
    public function destroy($id)
    {

        Project::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'massage' => 'the project was successfuly deleted '

        ]);
    }
    public function delete_by_selection(Request $request)
    {

        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $project_id) {
            Project::whereId($project_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json([
            'success' => true,
            'massage' => 'the projects was successfuly deleted '

        ]);
    }
    public function Create_project_discussions(Request $request)
    {

        $request->validate([
            'message'           => 'required|string',
        ]);

        $project = ProjectDiscussion::create([
            'message'            => $request['message'],
            'user_id'            => Auth::user()->id,
            'project_id'        => $request['project_id'],
        ]);

        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }

    public function destroy_project_discussion($id)
    {

        $projectDiscussion = ProjectDiscussion::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,

        ]);
    }
    //الي فاضل
    public function Create_project_issues(Request $request)
    {


        $request->validate([
            'title'         => 'required|string|max:255',
            'comment'       => 'required',
            'attachment'    => 'nullable|file|mimes:pdf,docs,doc,pptx,jpeg,png,jpg,bmp,gif,svg|max:2048',

        ]);


        if ($request->hasFile('attachment')) {

            $image = $request->file('attachment');
            $attachment = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/projects/issues'), $attachment);
        } else {
            $attachment = Null;
        }

        $projectIssue = ProjectIssue::create([
            'title'            => $request['title'],
            'project_id'       => $request['project_id'],
            'comment'          => $request['comment'],
            'label'            => $request['label'],
            'status'           => 'pending',
            'attachment'       => $attachment,
        ]);

        return response()->json([
            'success' => true,
            'projectIssue' => $projectIssue
        ]);
    }
    public function Update_project_issues(Request $request, $id)
    {

        $request->validate([
            'title'         => 'required|string|max:255',
            'comment'       => 'required',
            'attachment'    => 'nullable|file|mimes:pdf,docs,doc,pptx,jpeg,png,jpg,bmp,gif,svg|max:2048',

        ]);

        //upload attachment

        $project_issue = ProjectIssue::findOrFail($id);

        $currentAttachment = $project_issue->attachment;

        if ($request->attachment != null) {
            if ($request->attachment != $currentAttachment) {

                $image = $request->file('attachment');
                $attachment = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/projects/issues'), $attachment);
                $path = public_path() . '/assets/images/projects/issues';
                $project_issue_attachment = $path . '/' . $currentAttachment;
                if (file_exists($project_issue_attachment)) {
                    @unlink($project_issue_attachment);
                }
            } else {
                $attachment = $currentAttachment;
            }
        } else {
            $attachment = $currentAttachment;
        }

        ProjectIssue::whereId($id)->update([
            'title'            => $request['title'],
            'project_id'       => $request['project_id'],
            'comment'          => $request['comment'],
            'label'            => $request['label'],
            'status'           => $request['status'],
            'attachment'       => $attachment,
        ]);

        return response()->json([
            'success' => true,
           'project_issues' => $project_issue
        ]);
    }
    public function destroy_project_issues($id)
    {

        $projectIssue= ProjectIssue::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);

            return response()->json([
                'success' => true,
                'projectIssue'=>$projectIssue
            ]);
    }
    public function Create_project_documents(Request $request)
    {

            $request->validate([
                'title'         => 'required|string|max:255',
                'attachment'    => 'required|file|mimes:pdf,docs,doc,pptx,jpeg,png,jpg,bmp,gif,svg|max:2048',

            ]);


            if ($request->hasFile('attachment')) {

                $image = $request->file('attachment');
                $attachment = time().'.'.$image->extension();
                $image->move(public_path('/assets/images/projects/documents'), $attachment);

            } else {
                $attachment = Null;
            }

           $Project_document = ProjectDocument::create([
                'title'            => $request['title'],
                'project_id'       => $request['project_id'],
                'description'      => $request['description'],
                'attachment'       => $attachment,
            ]);

            return response()->json([
                'success' => true,
                'Project_document'=> $Project_document
            ]);


    }


    public function destroy_project_documents($id)
    {


            ProjectDocument::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);


    }
}


