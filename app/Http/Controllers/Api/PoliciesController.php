<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Policy;
use Carbon\Carbon;

class PoliciesController extends Controller
{
    public function index()
    {

        $policies = Policy::where('deleted_at', '=', null)->orderBy('id', 'desc')->paginate(50);
        return response()->json([
            'success' => true,
            'data' =>  $policies
        ]);
    }


    public function store(Request $request)
    {


        $request->validate([
            'title'        => 'required|string|max:255',
            'company_id'   => 'required',
            'description'  => 'required',
        ]);

        $policy = Policy::create([
            'title'        => $request['title'],
            'company_id'   => $request['company_id'],
            'description'  => $request['description'],
        ]);

        return response()->json([
            'success' => true,
            'policy' => $policy,

        ]);
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {


        $request->validate([
            'title'        => 'required|string|max:255',
            'company_id'   => 'required',
            'description'  => 'required|string',
        ]);

        Policy::whereId($id)->update([
            'title'        => $request['title'],
            'company_id'   => $request['company_id'],
            'description'  => $request['description'],
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


        Policy::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {

        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $policy_id) {
            Policy::whereId($policy_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
    }
}
