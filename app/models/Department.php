<?php

namespace App\models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $table = "departments";

    public function hospital()
    {
        return $this->belongsTo('App\models\Hospital', 'hospitals_id', 'id');
    }

    public function patientHistories()
    {
        return $this->hasMany(PatientHistory::class, 'department_id');
    }

    public function changePatientTypes()
    {
        return $this->hasMany(ChangePatientType::class, 'department_id');
    }

    /**
     * BN cũ
     *
     * @return HasMany
     */
    public function oldPatients()
    {
        $query = $this->patientHistories();

        return $query->where('time_go_in','<', Carbon::now()->format('yy-m-d'))
            ->whereIn('patient_state', [
                PatientHistory::TT_TRONG_VIEN,
                PatientHistory::TT_CHO_NHAP_VIEN,
                PatientHistory::TT_DA_DUOC_KET_LUAN_KHAM,
                PatientHistory::TT_DA_LAP_BENH_AN,
                PatientHistory::TT_DA_THANH_TOAN_VIEN_PHI,
            ]);
    }

    /**
     * BN vào viện
     *
     * @return HasMany
     */
    public function patientsInHospital()
    {
        $query = $this->patientHistories();

        return $query->where('time_go_in', Carbon::now()->format('yy-m-d'))
            ->whereIn('patient_state', [
                PatientHistory::TT_CHO_NHAP_VIEN,
            ]);
    }

    /**
     * BN ra viện
     *
     * @return HasMany
     */
    public function patientsDischargedFromHospital()
    {
        $query = $this->patientHistories();

        return $query->whereNotNull('time_go_out')
            ->whereIn('patient_state', [
                PatientHistory::TT_DA_THANH_TOAN_VIEN_PHI,
                PatientHistory::TT_DA_RA_VIEN_VA_CHUA_THANH_TOAN,
                PatientHistory::TT_RA_VIEN_TAM_THOI,
            ]);
    }

    /**
     * BN ngoại trú chuyển đến
     *
     * @return HasMany
     */
    public function outPatientTransferredTo()
    {
        return $this->changePatientTypes()
            ->has('patient.outPatients')
            ->whereNotNull('patient_from_hospital_id');
    }

    /**
     * BN nội trú chuyển đến
     *
     * @return HasMany
     */
    public function boardingPatientTransferredTo()
    {
        return $this->changePatientTypes()
            ->has('patient.boarding')
            ->whereNotNull('patient_from_hospital_id');
    }

    /**
     * Bn chuyển viện
     *
     * @return HasMany
     */
    public function referralPatient()
    {
        $query = $this->patientHistories();

        return $query->whereIn('patient_state', [
                PatientHistory::TT_TINH_TAO,
            ]);
    }

    /**
     * Bn chuyển khoa
     *
     * @return HasMany
     */
    public function transferDepartment()
    {
        $query = $this->patientHistories();

        return $query->where(function ($query) {
            $query->whereNotNull('transfer_department_date')
                ->orWhere('p_department_id', '!=', 'department_id');
        });
    }
}
