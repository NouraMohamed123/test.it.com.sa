<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $guarded = [];


    // protected $fillable = [
    //     "name",'email','phone','country'
    // ];

    public function users()
    {
        return $this->hasMany('App\Models\User');

    }

}
