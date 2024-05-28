<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArrangementType;
use Carbon\Carbon;

class ArrangementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

            $arrangement_types = ArrangementType::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
            return response()->json([
                'success' => true,
                'data' => $arrangement_types
            ]);


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


            request()->validate([
                'title'           => 'required|string|max:255',
            ]);

            ArrangementType::create([
                'title'           => $request['title'],
            ]);

            return response()->json(['success' => true]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $arrangementType = ArrangementType::find($id);

    if (!$arrangementType || $arrangementType->deleted_at) {
        return response()->json(['success' => false, 'message' => 'Arrangement type not found'], 404);
    }

    return response()->json(['success' => true, 'data' => $arrangementType]);
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


            request()->validate([
                'title'           => 'required|string|max:255',
            ]);

            ArrangementType::whereId($id)->update([
                'title'           => $request['title'],
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


            ArrangementType::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);


    }

     //-------------- Delete by selection  ---------------\\

     public function delete_by_selection(Request $request)
     {

            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $arrangement_id) {
                ArrangementType::whereId($arrangement_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        }

}
