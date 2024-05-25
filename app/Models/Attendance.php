<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'employee_id','company_id','date','clock_in',
        'clock_in_ip','clock_out_ip',
        'clock_in_out','depart_early','late_time','clock_out',
        'overtime','total_work','total_rest','status','work_type'

    ];

    protected $casts = [
        'employee_id'  => 'integer',
        'company_id'  => 'integer',
        'clock_in_out'  => 'integer',
    ];




    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }


}
