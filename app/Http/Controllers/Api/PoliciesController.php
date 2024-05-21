<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliciesController extends Controller
class PoliciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = auth()->user();
		if ($user_auth->can('policy_view')){
                $policies = Policy::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
                return view('core_company.policy.policy_list', compact('policies'));
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_auth = auth()->user();
		if ($user_auth->can('policy_add')){

            $companies = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','name']);
            return response()->json([
                'companies' =>$companies,
            ]);

        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


            $request->validate([
                'title'        => 'required|string|max:255',
                'company_id'   => 'required',
                'description'  => 'required',
            ]);

        $policy=Policy::create([
                'title'        => $request['title'],
                'company_id'   => $request['company_id'],
                'description'  => $request['description'],
            ]);

            return response()->json([
                'success' => true ,
                'policy' => $policy ,

            ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
