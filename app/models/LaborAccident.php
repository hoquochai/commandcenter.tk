<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class LaborAccident extends Model
{
	protected $table = "complains";
    public $timestamps = false;

    public function reportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }
}
