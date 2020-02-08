<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AssaultedStaff extends Model
{
    protected $table = "assaulted_staffs";
    public $timestamps = false;
    protected $fillable = [
        'name', 'phone', 'birthday','gender','passports','departments_id','address','hospitals_id'
    ];
}
