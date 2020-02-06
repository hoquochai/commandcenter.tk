<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class ChangePatientType extends Model
{
    protected $fillable = [
        'patient_id',
        'department_id',
        'patient_from_hospital_id'
    ];
}
