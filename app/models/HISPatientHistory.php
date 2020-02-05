<?php
namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bảng BN ngoại trú (bệnh nhân ngoại trú)
 * Class HISPatientHistory
 * @package App\models
 */
class HISPatientHistory extends Model
{
    protected $table='HIS_PatientHistory';

    public $timestamps = false;

    public function hisDepartment()
    {
        return $this->belongsTo(HISDepartment::class);
    }
}
