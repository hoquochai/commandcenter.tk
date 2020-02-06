<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TrendReport extends Model
{
    protected $fillable = [
        'id', 'date_trend_reports', 'date_input', 'result'
    ];
}
