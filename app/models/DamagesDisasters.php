<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class DamagesDisasters extends Model
{
    protected $table = 'damages_disasters';
    public $timestamps = false;
    public $fillable = [
    	'title',
		'date_report',
		'frequence',
		'report_types_id',
		'hospitals_id',
		'formality',
		'full_damages',
		'very_heavy_damage',
		'heavy_damage',
		'apart_damage',
		'under_water_less_1m',
		'under_water_1_3m',
		'under_water_than_3m',
		'damages_medicine',
		'PCLB',
		'ChloraminB',
		'life_jacket',
		'details',
		'attachments'
    ];
    public function ReportType()
	    {
	        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
	    }
}
