<?php

namespace App\Http\Controllers\Api;

use DB;
use DateTime;
use Exception;
use DataTables;
use Carbon\Carbon;
use App\utils\helpers;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $user_auth = Auth::guard('api')->user();
         $employee=  Employee::whereNull('deleted_at')->where('user_id', $user_auth->id)->first();
         if($employee && $employee->type == 1){
                $attendances = Attendance::where('deleted_at', '=', null)->where('employee_id', $employee->id)->orderBy('id', 'desc')->get();


            }else{
                $attendances = Attendance::where('deleted_at', '=', null)
                ->where('employee_id', '=', $user_auth->id)->orderBy('id', 'desc')->get();

            }
            return response()->json(['success' => true, 'data' => $attendances]);


    }




    public function daily_attendance(Request $request)
    {
        $user_auth = $request->user();
        $day_now = Carbon::now()->format('Y-m-d');
        $day_in_now = strtolower(Carbon::now()->format('l')) . '_in';

        if ($user_auth->role_users_id == 1) {
            $employee = Employee::with([
                'office_shift',
                'attendance' => function ($query) use ($day_now) {
                    $query->where('date', $day_now);
                },
                'office_shift',
                'company:id,name',
                'leave' => function ($query) use ($day_now) {
                    $query->where('start_date', '<=', $day_now)->where('end_date', '>=', $day_now);
                }
            ])
            ->select('id', 'company_id', 'username', 'office_shift_id')
            ->where('joining_date', '<=', $day_now)
            ->where('leaving_date', NULL)
            ->where('deleted_at', NULL)
            ->get();
        } else {
            $employee = Employee::with([
                'office_shift',
                'attendance' => function ($query) use ($day_now) {
                    $query->where('date', $day_now);
                },
                'office_shift',
                'company:id,name',
                'leave' => function ($query) use ($day_now) {
                    $query->where('start_date', '<=', $day_now)->where('end_date', '>=', $day_now);
                }
            ])
            ->select('id', 'company_id', 'username', 'office_shift_id')
            ->where('id', '=', $user_auth->id)
            ->where('joining_date', '<=', $day_now)
            ->where('leaving_date', NULL)
            ->where('deleted_at', NULL)
            ->get();
        }

        $holidays = Holiday::select('id', 'company_id', 'start_date', 'end_date')
            ->where('start_date', '<=', $day_now)
            ->where('end_date', '>=', $day_now)
            ->where('deleted_at', NULL)
            ->first();

        $data = $employee->map(function ($item) use ($day_now, $holidays, $day_in_now) {
            $attendance = $item->attendance->first();
            $status = $this->getAttendanceStatus($item, $holidays, $day_in_now);
            $clockIn = $attendance ? $attendance->clock_in : '---';
            $clockOut = $attendance ? $attendance->clock_out : '---';
            $lateTime = $attendance ? $attendance->late_time : '---';
            $departEarly = $attendance ? $attendance->depart_early : '---';
            $overtime = $this->calculateOvertime($item->attendance);
            $totalWork = $this->calculateTotalWork($item->attendance);
            $totalRest = $this->calculateTotalRest($item->attendance);

            return [
                'id' => $item->id,
                'username' => $item->username,
                'company' => $item->company->name,
                'date' => $attendance ? $attendance->date : Carbon::parse($day_now)->format('Y-m-d'),
                'status' => $status,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'late_time' => $lateTime,
                'depart_early' => $departEarly,
                'overtime' => $overtime,
                'total_work' => $totalWork,
                'total_rest' => $totalRest,
            ];
        });

        return response()->json([
            'data' => $data,
            'holidays' => $holidays ? $holidays->toArray() : null,
        ]);
    }

    private function getAttendanceStatus($employee, $holidays, $day_in_now)
    {
        if ($employee->attendance->isEmpty()) {
            if (is_null($employee->office_shift->$day_in_now ?? null) || ($employee->office_shift->$day_in_now == '')) {
                return 'Off Day';
            }
            if ($holidays) {
                if ($employee->company_id == $holidays->company_id) {
                    return 'Holiday';
                }
            }
            if ($employee->leave->isEmpty()) {
                return 'Absent';
            }
            return 'On leave';
        } else {
            $attendance = $employee->attendance->first();
            return $attendance->status;
        }
    }

    private function calculateOvertime($attendances)
    {
        $total = 0;
        foreach ($attendances as $attendance) {
            sscanf($attendance->overtime, '%d:%d', $hour, $min);
            $total += $hour * 60 + $min;
        }
        if ($h = floor($total / 60)) {
            $total %= 60;
        }
        return sprintf('%02d:%02d', $h, $total);
    }

    private function calculateTotalWork($attendances)
    {
        $total = 0;
        foreach ($attendances as $attendance) {
            sscanf($attendance->total_work, '%d:%d', $hour, $min);
            $total += $hour * 60 + $min;
        }
        if ($h = floor($total / 60)) {
            $total %= 60;
        }
        return sprintf('%02d:%02d', $h, $total);
    }

    private function calculateTotalRest($attendances)
    {
        $total = 0;
        foreach ($attendances as $attendance) {
            sscanf($attendance->total_rest, '%d:%d', $hour, $min);
            $total += $hour * 60 + $min;
        }
        if ($h = floor($total / 60)) {
            $total %= 60;
        }
        return sprintf('%02d:%02d', $h, $total);
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
		// if ($user_auth->can('attendance_add')){

            $this->validate($request, [
                'company_id'      => 'required',
                'employee_id'      => 'required',
                'date'           => 'required',
                'clock_in'      => 'required',
                'clock_out'      => 'required',
            ]);


            $employee_id  = $request->employee_id;
            $date  = $request->date;
            $company_id  = $request->company_id;
            $clock_in  = $request->clock_in;
            $clock_out  = $request->clock_out;

            try{
                $clock_in  = new DateTime($clock_in);
                $clock_out  = new DateTime($clock_out);
            }catch(Exception $e){
                return $e;
            }


            $employee = Employee::with('office_shift')->findOrFail($employee_id);

            $day_now = Carbon::parse($request->date)->format('l');
            $day_in_now = strtolower($day_now) . '_in';
            $day_out_now = strtolower($day_now) . '_out';

            $shift_in = $employee->office_shift->$day_in_now;
            $shift_out = $employee->office_shift->$day_out_now;

            if($shift_in ==null){
                $data['employee_id'] = $employee_id;
                $data['company_id'] = $company_id;
                $data['date'] = $date;
                $data['clock_in'] = $clock_in->format('H:i');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['status'] = 'present';

                $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
                $data['total_work'] = $work_duration;
                $data['depart_early'] = '00:00';
                $data['late_time'] = '00:00';
                $data['overtime'] = '00:00';
                $data['clock_in_out'] = 0;

            }

            try{
                $shift_in  = new DateTime(substr($shift_in, 0, -2));
                $shift_out  = new DateTime(substr($shift_out, 0, -2));
            }catch(Exception $e){
                return $e;
            }

            $data['employee_id'] = $employee_id;
            $data['date'] = $date;

            if($clock_in > $shift_in){
                $time_diff = $shift_in->diff($clock_in)->format('%H:%I');
                $data['clock_in'] = $clock_in->format('H:i');
                $data['late_time'] = $time_diff;
            }else{
                $data['clock_in'] = $shift_in->format('H:i');
                $data['late_time'] = '00:00';
            }


            if($clock_out < $shift_out){
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['depart_early'] = $time_diff;

            }elseif($clock_out > $shift_out){
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['overtime'] = $time_diff;
                $data['depart_early'] = '00:00';
            }else{
                $data['clock_out'] = $shift_out->format('H:i');
                $data['overtime'] = '00:00';
                $data['depart_early'] = '00:00';
            }

            $data['status'] = 'present';
            $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
            $data['total_work'] = $work_duration;
            $data['clock_in_out'] = 0;
            $data['company_id'] = $company_id;

            $data['clock_in_ip'] = '';
            $data['clock_out_ip'] = '';

            Attendance::create($data);

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
		// if ($user_auth->can('attendance_edit')){

            $this->validate($request, [
                'company_id'      => 'required',
                'employee_id'      => 'required',
                'date'           => 'required',
                'clock_in'      => 'required',
                'clock_out'      => 'required',
            ]);

            $employee_id  = $request->employee_id;
            $date  = $request->date;
            $clock_in  = $request->clock_in;
            $clock_out  = $request->clock_out;

            try{
                $clock_in  = new DateTime($clock_in);
                $clock_out  = new DateTime($clock_out);
            }catch(Exception $e){
                return $e;
            }

            $day_now = Carbon::parse($request->date)->format('l');

            $employee = Employee::with('office_shift')->findOrFail($employee_id);

            $day_in_now = strtolower($day_now) . '_in';
            $day_out_now = strtolower($day_now) . '_out';

            $shift_in = $employee->office_shift->$day_in_now;
            $shift_out = $employee->office_shift->$day_out_now;

            if($shift_in ==null){
                $data['employee_id'] = $employee_id;
                $data['date'] = $date;
                $data['clock_in'] = $clock_in->format('H:i');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['status'] = 'present';

                $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
                $data['total_work'] = $work_duration;
                $data['depart_early'] = '00:00';
                $data['late_time'] = '00:00';
                $data['overtime'] = '00:00';
                $data['clock_in_out'] = 0;

                return $data;

            }

            try{
                $shift_in  = new DateTime($shift_in);
                $shift_out  = new DateTime($shift_out);
            }catch(Exception $e){
                return $e;
            }

            $data['employee_id'] = $employee_id;
            $data['date'] = $date;

            if($clock_in > $shift_in){
                $time_diff = $shift_in->diff($clock_in)->format('%H:%I');
                $data['clock_in'] = $clock_in->format('H:i');
                $data['late_time'] = $time_diff;
            }else{
                $data['clock_in'] = $shift_in->format('H:i');
                $data['late_time'] = '00:00';
            }


            if($clock_out < $shift_out){
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['depart_early'] = $time_diff;

            }elseif($clock_out > $shift_out){
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['overtime'] = $time_diff;
                $data['depart_early'] = '00:00';
            }else{
                $data['clock_out'] = $shift_out->format('H:i');
                $data['overtime'] = '00:00';
                $data['depart_early'] = '00:00';
            }

            $data['status'] = 'present';
            $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
            $data['total_work'] = $work_duration;
            $data['clock_in_out'] = 0;


            Attendance::find($id)->update($data);

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
		// if ($user_auth->can('attendance_delete')){

            Attendance::whereId($id)->update([
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
        // if($user_auth->can('attendance_delete')){
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $attendance_id) {
                Attendance::whereId($attendance_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        // }
        // return abort('403', __('You are not authorized'));
     }
}
