<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TrendReport extends Model
{
    protected $fillable = [
        'id', 'date_trend_reports', 'result', 'users_id', 'received_id',
        'date_urgent_report', 'date_assaulted_staff', 'date_complain', 'date_labor_accident'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_id');
    }
}
