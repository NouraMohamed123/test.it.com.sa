<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use DB;

class DepartmentsController extends Controller
{
    public function index()
    {

        $department = Department::leftjoin('employees', 'employees.id', '=', 'departments.department_head')
            ->join('companies', 'companies.id', '=', 'departments.company_id')
            ->where('departments.deleted_at', '=', null)
            ->select('departments.*', 'employees.username AS employee_head', 'companies.name AS company_name')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'department' => $department
        ]);
    }
    public function store(Request $request)
    {


        request()->validate([
            'department'   => 'required|string|max:255',
            'company_id'   => 'required',
        ]);

        $department = Department::create([
            'department'        => $request['department'],
            'company_id'        => $request['company_id'],
            'department_head'   => $request['department_head'] ? $request['department_head'] : Null,
        ]);

        return response()->json([
            'success' => true,
            'department' => $department

        ]);
    }
    public function update(Request $request, $id)
    {


        request()->validate([
            'department'   => 'required|string|max:255',
            'company_id'   => 'required',
        ]);

         Department::whereId($id)->update([
            'department'        => $request['department'],
            'company_id'        => $request['company_id'],
            'department_head'   => $request['department_head'] ? $request['department_head'] : Null,
        ]);
        $updatedDepartment = Department::findOrFail($id);


        return response()->json([
            'success' => true,
            'department' => $updatedDepartment

        ]);
    }
    public function destroy($id)
    {


            Department::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['success' => true]);

        
    }

      //-------------- Delete by selection  ---------------\\

      public function delete_by_selection(Request $request)
      {

             $selectedIds = $request->selectedIds;

             foreach ($selectedIds as $department_id) {
                Department::whereId($department_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
             }
             return response()->json([
                'success' => true ,


            ]);
      }

    public function Get_all_Departments()
    {
        $departments = Department::where('deleted_at', '=', null)
        ->orderBy('id', 'desc')
        ->get(['id','department']);

        return response()->json([
            'success' => true ,
            'department'=> $departments

        ]);
    }



    public function Get_departments_by_company(Request $request)
    {
        $departments = Department::where('company_id' , $request->id)
        ->where('deleted_at', '=', null)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'success' => true ,
            'department'=> $departments

        ]);

    }
}
