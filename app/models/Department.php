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
     * Bệnh nhân ngoại trú
     */
    public function outPatients()
    {
        return $this->hasMany(PatientHistory::class, 'department_id')
            ->where('is_inpatient', false);
    }

    /**
     * BN nội trú
     */
    public function boarding()
    {
        return $this->hasMany(PatientHistory::class, 'department_id')->where('is_inpatient', true);
    }

    /**
     * BN cũ
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function oldPatients($isInpatient = false)
    {
        $query = $isInpatient ? $this->boarding() : $this->outPatients();

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
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function patientsInHospital($isInpatient = false)
    {
        $query = $isInpatient ? $this->boarding() : $this->outPatients();

        return $query->where('time_go_in', Carbon::now()->format('yy-m-d'))
            ->whereIn('patient_state', [
                PatientHistory::TT_CHO_NHAP_VIEN,
            ]);
    }

    /**
     * BN ra viện
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function patientsDischargedFromHospital($isInpatient = false)
    {
        $query = $isInpatient ? $this->boarding() : $this->outPatients();

        return $query->whereNotNull('time_go_out')
            ->whereIn('patient_state', [
                PatientHistory::TT_DA_THANH_TOAN_VIEN_PHI,
                PatientHistory::TT_DA_RA_VIEN_VA_CHUA_THANH_TOAN,
                PatientHistory::TT_RA_VIEN_TAM_THOI,
            ]);
    }

    /**
     * BN chuyển đến
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function patientTransferredTo($isInpatient = false)
    {
        return $this->changePatientTypes()
            ->has($isInpatient ? 'patient.boarding' : 'patient.outPatients')
            ->whereNotNull('patient_from_hospital_id');
    }

    /**
     * Bn chuyển viện
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function referralPatient($isInpatient = false)
    {
        $query = $isInpatient ? $this->boarding() : $this->outPatients();

        return $query->whereIn('patient_state', [
                PatientHistory::TT_TINH_TAO,
            ]);
    }

    /**
     * Bn chuyển khoa
     * @param boolean $isInpatient
     * @return HasMany
     */
    public function transferDepartment($isInpatient = false)
    {
        $query = $isInpatient ? $this->boarding() : $this->outPatients();

        return $query->whereNotNull('transfer_department_date');
    }
}
