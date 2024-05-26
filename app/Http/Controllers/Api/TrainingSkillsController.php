<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TrainingSkill;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrainingSkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $user_auth = Auth::guard('api')->user();


            $training_skills = TrainingSkill::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
            return response()->json(['success' => true, 'data' => $training_skills]);



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
		// if ($user_auth->can('training_skills')){

            $request->validate([
                'training_skill'   => 'required|string|max:255',

            ]);

            TrainingSkill::create([
                'training_skill'  => $request['training_skill'],
            ]);

            return response()->json(['success' => true]);

        // }
        // return abort('403', __('You are not authorized'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $training_skill =  TrainingSkill::where('deleted_at', '=', null)->findOrFail($id);
        return response()->json(['success' => true, 'data' => $training_skill]);

    }

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
		// if ($user_auth->can('training_skills')){

            $request->validate([
                'training_skill'   => 'required|string|max:255',

            ]);

            TrainingSkill::whereId($id)->update([
                'training_skill'  => $request['training_skill'],
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
		// if ($user_auth->can('training_skills')){

            TrainingSkill::whereId($id)->update([
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
        //  if($user_auth->can('training_skills')){
             $selectedIds = $request->selectedIds;

             foreach ($selectedIds as $training_skills_id) {
                TrainingSkill::whereId($training_skills_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
             }
             return response()->json(['success' => true]);
        //  }
        //  return abort('403', __('You are not authorized'));
      }
}
