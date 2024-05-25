<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Attendance;
use App\Models\Project;
use App\Models\Task;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Deposit;
use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\DepositCategory;
use Carbon\Carbon;
use DB;
use App\utils\helpers;
use DataTables;

class ReportController extends Controller
{
    public function attendance_report_index(Request $request)
    {


        $helpers = new helpers();
        $param = array(0 => '=');
        $columns = array(0 => 'employee_id');
        $end_date_default = Carbon::now()->format('Y-m-d');
        $start_date_default = Carbon::now()->subYear()->format('Y-m-d');
        $start_date = empty($request->start_date) ? $start_date_default : $request->start_date;
        $end_date = empty($request->end_date) ? $end_date_default : $request->end_date;

        $attendances = Attendance::where('deleted_at', '=', null)
            ->whereBetween('date', array($start_date, $end_date))
            ->with('company:id,name', 'employee:id,username')
            ->orderBy('id', 'desc');

        //Multiple Filter
        $attendances_Filtred = $helpers->filter($attendances, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $attendances_Filtred
        ]);
    }
    public function employee_report_index(Request $request)
    {


        $helpers = new helpers();
        $param = array(0 => '=', 1 => '=', 2 => '=');
        $columns = array(0 => 'company_id', 1 => 'department_id', 2 => 'designation_id');

        $employees = Employee::where('deleted_at', '=', null)
            ->with('company:id,name', 'department:id,department', 'designation:id,designation', 'office_shift:id,name')
            ->orderBy('id', 'desc');

        // Multiple Filter
        $employees_Filtred = $helpers->filter($employees, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $employees_Filtred
        ]);
    }
    public function project_report_index(Request $request)
    {


        $helpers = new helpers();
        $param = array(0 => 'like', 1 => '=', 2 => '=', 3 => 'like', 4 => 'like');
        $columns = array(0 => 'title', 1 => 'client_id', 2 => 'company_id', 3 => 'priority', 4 => 'status');

        $projects = Project::where('deleted_at', '=', null)
            ->with('company:id,name', 'client:id,username')
            ->orderBy('id', 'desc');

        // Multiple Filter
        $projects_Filtred = $helpers->filter($projects, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $projects_Filtred
        ]);
    }
    public function task_report_index(Request $request)
    {


        $helpers = new helpers();
        $param = array(0 => 'like', 1 => '=', 2 => '=', 3 => 'like', 4 => 'like');
        $columns = array(0 => 'title', 1 => 'project_id', 2 => 'company_id', 3 => 'priority', 4 => 'status');

        $tasks = Task::where('deleted_at', '=', null)
            ->with('company:id,name', 'project:id,title')
            ->orderBy('id', 'desc');

        // Multiple Filter
        $tasks_Filtred = $helpers->filter($tasks, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $tasks_Filtred
        ]);
    }
    public function expense_report_index(Request $request)
    {

        $helpers = new helpers();
        $param = array(0 => 'like', 1 => '=', 2 => '=', 3 => '=');
        $columns = array(0 => 'expense_ref', 1 => 'account_id', 2 => 'expense_category_id', 3 => 'payment_method_id');
        $end_date_default = Carbon::now()->format('Y-m-d');
        $start_date_default = Carbon::now()->subYear()->format('Y-m-d');
        $start_date = empty($request->start_date) ? $start_date_default : $request->start_date;
        $end_date = empty($request->end_date) ? $end_date_default : $request->end_date;

        $expenses = Expense::where('deleted_at', '=', null)
            ->whereBetween('date', [$start_date, $end_date])
            ->with('account:id,account_name', 'payment_method:id,title', 'expense_category:id,title')
            ->orderBy('id', 'desc');

        // Multiple Filter
        $expenses_Filtred = $helpers->filter($expenses, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $expenses_Filtred
        ]);
    }
    public function deposit_report_index(Request $request)
    {

        $helpers = new helpers();
        $param = array(0 => 'like', 1 => '=', 2 => '=', 3 => '=');
        $columns = array(0 => 'deposit_ref', 1 => 'account_id', 2 => 'deposit_category_id', 3 => 'payment_method_id');
        $end_date_default = Carbon::now()->format('Y-m-d');
        $start_date_default = Carbon::now()->subYear()->format('Y-m-d');
        $start_date = empty($request->start_date) ? $start_date_default : $request->start_date;
        $end_date = empty($request->end_date) ? $end_date_default : $request->end_date;

        $deposits = Deposit::where('deleted_at', '=', null)
            ->whereBetween('date', array($start_date, $end_date))
            ->with('account:id,account_name', 'payment_method:id,title', 'deposit_category:id,title')
            ->orderBy('id', 'desc');

        // Multiple Filter
        $deposits_Filtred = $helpers->filter($deposits, $columns, $param, $request)->get();

        return response()->json([
            'success' => true,
            'data' => $deposits_Filtred
        ]);
    }
    public function fetchDepartment(Request $request){

        $value = $request->get('company_id');
        $data = Department::where('company_id' ,$value)->where('deleted_at', '=', null)->groupBy('department')->get();
        return $data;
    }


    public function fetchDesignation(Request $request){
        $value = $request->get('department_id');
        $data = Designation::where('department_id' ,$value)->where('deleted_at', '=', null)->groupBy('designation')->get();
        return $data;
    }
}
