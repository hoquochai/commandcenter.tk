<?php
namespace App\models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Danh mục khoa
 * Class HISDepartment
 * @package App\models
 */
class HISDepartment extends Model
{
    protected $table='HIS_Department';

    public $timestamps = false;

    public function hisPatientHistories()
    {
        return $this->hasMany(HISPatientHistory::class, 'HIS_Department_ID');
    }

    public function oldPatients()
    {
        return $this->hasMany(HISPatientHistory::class, 'HIS_Department_ID')
            ->where('TimeGoIn','>', Carbon::now()->format('yy-m-d'))
            ->whereIn('PatientState', [
                'Trong viện',
                'Chờ nhập viện',
                'Đã được kết luận khám',
                'Đã lập bệnh án',
                'Đã thanh toán viện phí'
            ]);
    }

    public function patientsInHospital()
    {
        return $this->hasMany(HISPatientHistory::class, 'HIS_Department_ID')
            ->where('TimeGoIn', Carbon::now()->format('yy-m-d'))
            ->whereIn('PatientState', [
                'Chờ nhập viện'
            ]);
    }

    public function patientsDischargedFromHospital()
    {
        return $this->hasMany(HISPatientHistory::class, 'HIS_Department_ID')
            ->whereNotNull('TimeGoOut')
            ->whereIn('PatientState', [
                'Đã thanh toán viện phí',
                'Đã ra viện và chưa thanh toán',
                'Ra viện tạm thời'
            ]);
    }

    public function patienTransferredTo()
    {
//        return $this->hasMany(HISPatientHistory::class, 'HIS_Department_ID')
//            ->whereNotNull('TimeGoOut')
//            ->whereIn('PatientState', [
//                'Đã thanh toán viện phí',
//                'Đã ra viện và chưa thanh toán',
//                'Ra viện tạm thời'
//            ]);
    }
}
