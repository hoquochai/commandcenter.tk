<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Assaulted extends Model
{
	protected $table = "assaulted";
    public $timestamps = false;
    protected $fillable = [
        'title','date_assaulted','frequence','report_types_id','assaulted_staff_id','hospitals_id',
        'formality', 'attachments', 'assault_case', 'reason', 'information_person', 'details', 'resolution_no',
        'from_date', 'to_date', 'infomation_abuser', 'verified_content','conclude',
        'petition', 'person_responsible'
    ];
      public function ReportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }
}
