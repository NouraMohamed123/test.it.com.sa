<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Policy;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PoliciesController extends Controller
{
    public function index()
    {
        $user_auth = Auth::guard('api')->user();

        $employee =  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
        if ($employee && $employee->type == 1) {
            $policies = Policy::where('deleted_at', '=', null)->where('company_id', $employee->company->id)->orderBy('id', 'desc')->paginate(50);
        } else {
            $policies = Policy::where('deleted_at', '=', null)->orderBy('id', 'desc')->paginate(50);
        }

        return response()->json([
            'success' => true,
            'data' =>  $policies
        ]);
    }
    public function show(Request $request, $id)
    {
        $policy = Policy::where('id', $id)
            ->where('deleted_at', null)
            ->first();
        if ($policy) {
            return response()->json([
                'success' => true,
                'data' => $policy
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'policy not found'
            ], 404);
        }
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
