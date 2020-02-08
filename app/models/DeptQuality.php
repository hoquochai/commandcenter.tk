<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class DeptQuality extends Model
{
    protected $table = "dept_quality";
    public $timestamps = false;
    protected $fillable = [
        'title','date_report','frequence','report_types_id','hospitals_id',
        'formality', 'note','file'
    ];
    public function ReportType()
    {
        return $this->belongsTo('App\models\ReportType', 'report_types_id', 'id');
    }
}
