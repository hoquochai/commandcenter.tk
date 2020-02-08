<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = "patients";
    public $timestamps = false;

    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class, 'patient_id');
    }

    public function outPatients()
    {
        return $this->hasMany(PatientHistory::class, 'patient_id')->where('is_inpatient', false);
    }

    public function boarding()
    {
        return $this->hasMany(PatientHistory::class, 'patient_id')->where('is_inpatient', true);
    }
}
