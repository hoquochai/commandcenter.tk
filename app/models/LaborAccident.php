<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class LaborAccident extends Model
{
	protected $table = "labor_accidents";
    public $timestamps = false;
    protected $fillable = [
        'title','date_report','frequence','report_types_id','patients_id','hospitals_id',
        'formality', 'totals_accidents', 'women_labor_accidents', 'number_accidents', 'number_labor_accidents','number_died_people','number_serious_people','totals_salary_fund','employer','details','damages','fees_moneys','total_fees','salary_during_treatment','depts_specific_expenses','indemnify','demages_assets','file'
    ];
    public function ReportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }
}
