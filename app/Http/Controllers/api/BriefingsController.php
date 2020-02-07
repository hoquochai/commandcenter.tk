<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\models\Department;

class BriefingsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $outHisDepartments = Department::withCount([
            'oldPatients' =>  function($query) {
                $query->where('is_inpatient', false);
            },
            'patientsInHospital' =>  function($query) {
                $query->where('is_inpatient', false);
            },
            'patientsDischargedFromHospital' =>  function($query) {
                $query->where('is_inpatient', false);
            },
            'outPatientTransferredTo',
            'referralPatient' =>  function($query) {
                $query->where('is_inpatient', false);
            },
            'transferDepartment' =>  function($query) {
                $query->where('is_inpatient', false);
            }
        ])->get();

        foreach ($outHisDepartments as $outHisDepartment) {
            $outHisDepartment->current = $outHisDepartment->old_patients_count + $outHisDepartment->patients_in_hospital_count;
        }
        $boardingHisDepartments = Department::withCount([
            'oldPatients' =>  function($query) {
                $query->where('is_inpatient', true);
            },
            'patientsInHospital' =>  function($query) {
                $query->where('is_inpatient', true);
            },
            'patientsDischargedFromHospital' =>  function($query) {
                $query->where('is_inpatient', true);
            },
            'boardingPatientTransferredTo',
            'referralPatient' =>  function($query) {
                $query->where('is_inpatient', true);
            },
            'transferDepartment' =>  function($query) {
                $query->where('is_inpatient', true);
            }
        ])->get();
        foreach ($boardingHisDepartments as $boardingHisDepartment) {
            $boardingHisDepartment->current = $boardingHisDepartment->old_patients_count + $boardingHisDepartment->patients_in_hospital_count;
        }
        return response()->json([
            'data'=> [
                'bn_ngoai_tru' => $outHisDepartments,
                'bn_noi_tru' => $boardingHisDepartments
            ]
        ], $this->successStatus);
    }
}
