<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title','company_id','summary','start_date','end_date','priority',
        'description','project_progress','status','project_note',

    ];

    protected $casts = [

        'company_id'  => 'integer',
    ];



    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function assignedEmployees()
    {
        return $this->belongsToMany('App\Models\Employee');
    }
}
