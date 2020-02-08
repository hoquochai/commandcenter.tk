<?php

namespace App\Http\Controllers\api;

use App\models\Department;
use App\Http\Controllers\Controller;

class BriefingsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $departments = Department::withCount([
            'bn_ngoai_tru_cu',
            'bn_ngoai_tru_vao_vien',
            'bn_ngoai_tru_ra_vien',
            'bn_ngoai_tru_chuyen_den',
            'bn_ngoai_tru_chuyen_vien',
            'bn_ngoai_tru_chuyen_khoa',

            'bn_noi_tru_cu',
            'bn_noi_tru_vao_vien',
            'bn_noi_tru_ra_vien',
            'bn_noi_tru_chuyen_den',
            'bn_noi_tru_chuyen_vien',
            'bn_noi_tru_chuyen_khoa',
        ])->get();

        foreach ($departments as $department) {
            $departments->bn_ngoai_tru_hien_co_count = $department->bn_ngoai_tru_cu_count + $department->bn_ngoai_tru_vao_vien_count;
            $departments->bn_noi_tru_hien_co_count = $department->bn_noi_tru_cu_count + $department->bn_noi_tru_vao_vien_count;
        }
        return response()->json(['data'=> $departments], $this->successStatus);
    }
}
