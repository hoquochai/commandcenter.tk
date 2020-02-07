<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TrendReport extends Model
{
    protected $fillable = [
        'id', 'date_trend_reports', 'date_input', 'result', 'users_id', 'received_id'
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
