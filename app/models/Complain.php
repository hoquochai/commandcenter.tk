<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $table = "complains";
    public $timestamps = false;
    protected $fillable = [
        'title','date_complain','frequence','report_types_id','complainants_id','hospitals_id',
        'formality', 'file', 'content', 'reason', 'information_person', 'note', 'resolution_no',
        'from_date', 'to_date', 'requirement_of_complainant', 'verified_content','conclude',
        'petition', 'person_responsible'
    ];
      public function ReportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }
}
