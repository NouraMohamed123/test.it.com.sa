<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Travel;
use App\Models\ArrangementType;
use App\Models\Company;
use App\Models\Employee;
use Carbon\Carbon;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = Travel::with('company:id,name', 'employee:id,username', 'arrangement_type:id,title')
            ->where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $travels
        ]);
    }
    public function show($id)
    {
        $travel = Travel::with('company:id,name', 'employee:id,username', 'arrangement_type:id,title')
            ->where('id', '=', $id)
            ->where('deleted_at', '=', null)
            ->first();

        if ($travel) {
            return response()->json([
                'success' => true,
                'data' => $travel
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Travel not found or has been deleted'
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


        $this->validate($request, [
            'company_id'         => 'required',
            'employee_id'         => 'required',
            'arrangement_type_id' => 'required',
            'expected_budget'     => 'required|numeric',
            'actual_budget'       => 'required|numeric',
            'start_date'          => 'required',
            'end_date'            => 'required',
            'visit_purpose'       => 'required|string|max:255',
            'visit_place'         => 'required|string|max:255',
            'travel_mode'         => 'required|string|max:255',
            'status'            => 'required',
        ]);

        Travel::create([
            'company_id'         => $request['company_id'],
            'employee_id'         => $request['employee_id'],
            'arrangement_type_id' => $request['arrangement_type_id'],
            'expected_budget'     => $request['expected_budget'],
            'actual_budget'       => $request['actual_budget'],
            'start_date'          => $request['start_date'],
            'end_date'            => $request['end_date'],
            'visit_purpose'       => $request['visit_purpose'],
            'visit_place'         => $request['visit_place'],
            'travel_mode'         => $request['travel_mode'],
            'description'         => $request['description'],
            'status'            => $request['status'],
        ]);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {

        $companies   = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
        $arrangement_types = ArrangementType::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();

        return response()->json([
            'companies'         => $companies,
            'arrangement_types' => $arrangement_types,
        ]);


        return response()->json([
            'success' => true,

        ]);
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


        $this->validate($request, [
            'company_id'         => 'required',
            'employee_id'         => 'required',
            'arrangement_type_id' => 'required',
            'expected_budget'     => 'required|numeric',
            'actual_budget'       => 'required|numeric',
            'start_date'          => 'required',
            'end_date'            => 'required',
            'visit_purpose'       => 'required|string|max:255',
            'visit_place'         => 'required|string|max:255',
            'travel_mode'         => 'required|string|max:255',
            'status'              => 'required',
        ]);

        Travel::whereId($id)->update([
            'company_id'         => $request['company_id'],
            'employee_id'         => $request['employee_id'],
            'arrangement_type_id' => $request['arrangement_type_id'],
            'expected_budget'     => $request['expected_budget'],
            'actual_budget'       => $request['actual_budget'],
            'start_date'          => $request['start_date'],
            'end_date'            => $request['end_date'],
            'visit_purpose'       => $request['visit_purpose'],
            'visit_place'         => $request['visit_place'],
            'travel_mode'         => $request['travel_mode'],
            'description'         => $request['description'],
            'status'            => $request['status'],
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

        Travel::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {

        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $travel_id) {
            Travel::whereId($travel_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
    }
}
