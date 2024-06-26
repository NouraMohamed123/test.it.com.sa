<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];
     protected $guarded = [];

    public function jop()
    {
        return $this->belongsTo('App\Models\Jop');
    }
}
