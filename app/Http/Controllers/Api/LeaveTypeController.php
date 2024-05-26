<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user_auth = Auth::guard('api')->user();


            $leave_types = LeaveType::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
            return response()->json(['success' => true, 'data' => $leave_types]);



    }

    public function show(Request $request, $id)
    {
        $leave = LeaveType::
            where('id', $id)
            ->whereNull('deleted_at')
            ->first();

            if ($leave) {
                return response()->json([
                    'success' => true,
                    'data' => $leave
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'leave not found'
                ], 404);
            }
    }

    public function create()
    {
        //
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
		// if ($user_auth->can('leave_type')){

            request()->validate([
                'title'           => 'required|string|max:255',
            ]);

            LeaveType::create([
                'title'           => $request['title'],
            ]);

            return response()->json(['success' => true]);

    //     }
    //     return abort('403', __('You are not authorized'));
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
		// if ($user_auth->can('leave_type')){

            request()->validate([
                'title'           => 'required|string|max:255',
            ]);

            LeaveType::whereId($id)->update([
                'title'           => $request['title'],
            ]);

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
		// if ($user_auth->can('leave_type')){

            LeaveType::whereId($id)->update([
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
        // if($user_auth->can('leave_type')){
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $leave_type_id) {
                LeaveType::whereId($leave_type_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
     }
}
