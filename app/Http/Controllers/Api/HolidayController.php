<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Company;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $holidays = Holiday::where('deleted_at', '=', null)->orderBy('id', 'desc')->paginate(50);
        return response()->json([
            'success' => true,
            'data' => $holidays
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */




    public function store(Request $request)
    {


        request()->validate([
            'title'           => 'required|string|max:255',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'company_id'   => 'required',
        ]);

        Holiday::create([
            'company_id'   => $request['company_id'],
            'title'           => $request['title'],
            'start_date'      => $request['start_date'],
            'end_date'        => $request['end_date'],
            'description'     => $request['description'],
        ]);

        return response()->json(['success' => true]);
    }


    public function show($id)
    {
        $holiday = Holiday::find($id);

        if ($holiday && $holiday->deleted_at === null) {
            return response()->json([
                'success' => true,
                'data' => $holiday
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Holiday not found or has been deleted'
            ], 404);
        }
    }





    public function update(Request $request, $id)
    {


        request()->validate([
            'title'           => 'required|string|max:255',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'company_id'   => 'required',
        ]);

        Holiday::whereId($id)->update([
            'company_id'   => $request['company_id'],
            'title'           => $request['title'],
            'start_date'      => $request['start_date'],
            'end_date'        => $request['end_date'],
            'description'     => $request['description'],
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

        Holiday::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);
        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {

        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $holiday_id) {
            Holiday::whereId($holiday_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
    }
}
