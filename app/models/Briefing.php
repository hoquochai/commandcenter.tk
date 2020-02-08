<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Briefing extends Model
{
    const FREQUENCE_HANG_NGAY = 1;
    const FREQUENCE_HANG_TUAN = 2;
    const FREQUENCE_HANG_THANG = 3;

    protected $fillable = [
        'date_briefings', 'title', 'frequence', 'hospitals_id',
        'result', 'users_id', 'received_id', 'report_types_id'
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

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospitals_id');
    }

    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'report_types_id');
    }

    /**
     * @return string
     */
    public function getFrequence()
    {
        if ($this->frequence == self::FREQUENCE_HANG_NGAY) {
            return "Hàng ngày";
        } elseif ($this->frequence == self::FREQUENCE_HANG_TUAN) {
            return "Hàng tuần";
        } else {
            return "Hàng tháng";
        }
    }
}
