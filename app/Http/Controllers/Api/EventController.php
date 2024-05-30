<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = Auth::guard('api')->user();
        if($user_auth->type == 3  && $user_auth->type == 2){
            $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
            $events = Event::where('deleted_at', '=', null)->where('company_id',$employee->company->id)->orderBy('id', 'desc')->paginate(50);
        }else{
            $events = Event::where('deleted_at', '=', null)->orderBy('id', 'desc')->paginate(50);

        }
            return response()->json([
                'success' => true ,
                'data'=> $events
            ]);


    }
    public function show(Request $request, $id)
{
    $event = Event::where('id', $id)
        ->where('deleted_at', null)
        ->first();

        if ($event) {
            return response()->json([
                'success' => true,
                'data' => $event
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'event not found'
            ], 404);
        }
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
                'title'           => 'required|string|max:255',
                'date'            => 'required',
                'time'            => 'required',
                'status'            => 'required',
                'company_id'            => 'required',
                'department_id'            => 'required',
            ]);

            Event::create([
                'title'           => $request['title'],
                'date'            => $request['date'],
                'time'            => $request['time'],
                'note'            => $request['note'],
                'status'            => $request['status'],
                'company_id'     => $request['company_id'],
                'department_id'     => $request['department_id'],
            ]);

            return response()->json(['success' => true]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function update(Request $request, $id)
    {


            request()->validate([
                'title'           => 'required|string|max:255',
                'date'            => 'required',
                'time'            => 'required',
                'status'            => 'required',
                'company_id'            => 'required',
                'department_id'            => 'required',

            ]);

            Event::whereId($id)->update([
                'title'           => $request['title'],
                'date'            => $request['date'],
                'time'            => $request['time'],
                'note'            => $request['note'],
                'status'            => $request['status'],
                'company_id'     => $request['company_id'],
                'department_id'     => $request['department_id'],
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


            Event::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);


    }

       //-------------- Delete by selection  ---------------\\

       public function delete_by_selection(Request $request)
       {

              $selectedIds = $request->selectedIds;

              foreach ($selectedIds as $event_id) {
                Event::whereId($event_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
              }
              return response()->json(['success' => true]);

       }

}

