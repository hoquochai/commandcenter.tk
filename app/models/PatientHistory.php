<?php
namespace App\models;
use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    const TT_TRONG_VIEN = "Trong viện";
    const TT_CHO_NHAP_VIEN = "Chờ nhập viện";
    const TT_DA_DUOC_KET_LUAN_KHAM = "Đã được kết luận khám";
    const TT_DA_LAP_BENH_AN = "Đã lập bệnh án";
    const TT_DA_THANH_TOAN_VIEN_PHI = "Đã thanh toán viện phí";
    const TT_DA_RA_VIEN_VA_CHUA_THANH_TOAN = "Đã ra viện và chưa thanh toán";
    const TT_RA_VIEN_TAM_THOI = "Ra viện tạm thời";
    const TT_TINH_TAO = "Tỉnh táo";

    protected $fillable = [
        'patient_id',
        'department_id',
        'is_inpatient',
        'time_go_in',
        'patient_state',
        'time_go_out',
        'transfer_department_date',
        'p_department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function pDepartment()
    {
        return $this->belongsTo(Department::class, 'p_department_id');
    }
}
