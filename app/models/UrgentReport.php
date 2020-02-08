<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\ReportType;
class UrgentReport extends Model
{
    protected $table = "urgent_reports";
    public $timestamps = false;
    protected $fillable = [
        'title','date_report','frequence','report_types_id','patients_id','hospitals_id','departments_id','serious_problem_types_id','users_id','received_id',
        'formality','area','report_number',	'description', 'firstAid', 'proposed_solution','notify_doctor','notify_family', 'trouble_level',
        'recorded_medical','notify_patient','witnesses1','witnesses2','time_problem','date_problem','problem_object','note','file',
        'name_reporter','email_reporter','phone_reporter',	'type_reporter'
    ];
    
    public function ReportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patients_id');
    }
}
