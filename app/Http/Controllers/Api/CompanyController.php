<?php

namespace App\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('company_view')){

        $companies = Company::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['success' => true, 'data' => $companies]);


        // }
        // return abort('403', __('You are not authorized'));
    }

    public function store(Request $request)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('company_add')) {
        request()->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
            'tax_number' => 'required',
            'job_classification' => 'nullable|max:255',
            'trade_register' => 'nullable|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',

        ]);

        if ($request->hasFile('logo')) {

            $image = $request->file('logo');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/logo'), $filename);
        } else {
            $filename =  'no_avatar.png';
        }
        if ($request->hasFile('tax_number_photo')) {

            $image = $request->file('tax_number_photo');
            $filename1 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/tax_number_photo'), $filename);
        } else {
            $filename1 =   'no_avatar.png';
        }
        if ($request->hasFile('job_classification')) {

            $image = $request->file('job_classification');
            $filename2 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/job_classification'), $filename);
        } else {
            $filename2 =   'no_avatar.png';
        }
        if ($request->hasFile('trade_register')) {

            $image = $request->file('trade_register');
            $filename3 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/trade_register'), $filename);
        } else {
            $filename3 =   'no_avatar.png';
        }

        Company::create([
            'name' => $request['name'],
            'logo' => $filename,
            'status' => $request['status'],
            'tax_number' => $request['tax_number'],
            'tax_number_photo' =>  $filename1,
            'job_classification' => $filename2,
            'trade_register' => $filename3,
            'email' => $request['email'],
            'phone' => $request['phone'],

        ]);

        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }


    public function update(Request $request, $id)
    {
        $user_auth = Auth::guard('api')->user();
        // if ($user_auth->can('company_edit')) {
        request()->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|max:255',
            'status' => 'required|in:active,inactive',
            'tax_number' => 'required|string|digits:10',
            'job_classification' => 'nullable|string|max:255',
            'trade_register' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',

        ]);

        $company = Company::findOrFail($id);

        if ($request->hasFile('logo')) {

            $image = $request->file('logo');
            $filename = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/logo'), $filename);
        } else {
            $filename =  $company->logo;
        }
        if ($request->hasFile('tax_number_photo')) {

            $image = $request->file('tax_number_photo');
            $filename1 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/tax_number_photo'), $filename);
        } else {
            $filename1 = $company->tax_number_photo;
        }
        if ($request->hasFile('job_classification')) {

            $image = $request->file('job_classification');
            $filename2 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/job_classification'), $filename);
        } else {
            $filename2 =  $company->job_classification;
        }
        if ($request->hasFile('trade_register')) {

            $image = $request->file('trade_register');
            $filename3 = time() . '.' . $image->extension();
            $image->move(public_path('/assets/images/companies/trade_register'), $filename);
        } else {
            $filename3 =  $company->trade_register;
        }

        $company->update([
            'name' => $request['name'],
            'logo' => $filename,
            'status' => $request['status'],
            'tax_number' => $request['tax_number'],
            'tax_number_photo' =>  $filename1,
            'job_classification' => $filename2,
            'trade_register' => $filename3,
            'email' => $request['email'],
            'phone' => $request['phone'],

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
        // if ($user_auth->can('company_delete')){

        Company::whereId($id)->update([
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
        // if($user_auth->can('company_delete')){
        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $company_id) {
            Company::whereId($company_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
    }

    public function Get_all_Company()
    {
        $companies = Company::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get(['id', 'name']);

        return response()->json($companies);
    }
}
