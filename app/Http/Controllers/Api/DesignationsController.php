<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DesignationsController extends Controller
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
        if($employee && $employee->type == 3){
            $designations = Designation::with('department')
            ->where('deleted_at', '=', null)->where('company_id',$employee->company->id)
            ->orderBy('id', 'desc')
            ->get();
        }else{
            $designations = Designation::with('department')
            ->where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
        }

            return response()->json([
                'success' => true,
                'data' => $designations
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


            request()->validate([
                'designation'   => 'required|string|max:255',
                'company_id'   => 'required',
                'department'    => 'required',
            ]);

            Designation::create([
                'designation'   => $request['designation'],
                'company_id'        => $request['company_id'],
                'department_id' => $request['department'],
            ]);

            return response()->json(['success' => true]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   //
   public function show($id)
    {
        $designation = Designation::with('department')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $designation]);
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

            request()->validate([
                'designation'   => 'required|string|max:255',
                'company_id'   => 'required',
                'department'    => 'required',
            ]);

            Designation::whereId($id)->update([
                'designation'   => $request['designation'],
                'company_id'        => $request['company_id'],
                'department_id' => $request['department'],
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

            Designation::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['success' => true]);


    }

       //-------------- Delete by selection  ---------------\\

       public function delete_by_selection(Request $request)
       {

              $selectedIds = $request->selectedIds;

              foreach ($selectedIds as $designation_id) {
                    Designation::whereId($designation_id)->update([
                        'deleted_at' => Carbon::now(),
                    ]);
              }
              return response()->json(['success' => true]);

       }



    public function Get_designations_by_department(Request $request)
    {
        $designations = Designation::where('department_id' , $request->id)
        ->where('deleted_at', '=', null)
        ->orderBy('id', 'desc')
        ->get();


        return response()->json([
            'success' => true,
            'data' => $designations
        ]);
    }

}

