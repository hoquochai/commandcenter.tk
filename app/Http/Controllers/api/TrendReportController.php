<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\models\TrendReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrendReportController extends Controller
{
    public $successStatus = 200;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $hospitalId = $user->hospitals_id;
            $dateInput = $request->has('date') ? $request->get('date') : Carbon::now()->format('yy-m-d');

            $output['bao_cao_su_co_y_khoa_nghiem_trong'] = $this->statific('urgent_reports', 'date_report', $hospitalId, $dateInput);
            $output['bao_cao_nhan_vien_y_te_bi_hanh_hung'] = $this->statific('assaulted', 'date_assaulted', $hospitalId, $dateInput);
            $output['bao_cao_khieu_nai_khieu_kien'] = $this->statific('complains', 'date_complain', $hospitalId, $dateInput);
            $output['bao_cao_tai_nan_lao_dong'] = $this->statific('labor_accidents', 'Date_report', $hospitalId, $dateInput);

            //Save DB
            TrendReport::create([
                'date_trend_reports' => Carbon::now()->format('yy-m-d'),
                'date_input' => $dateInput,
                'result' => json_encode($output),
            ]);

            return response()->json(['data'=> $output], $this->successStatus);
        } catch (\Exception $exception) {
            return response()->json(['data'=> 'Xử lý thất bại'], $exception->getCode());
        }
    }

    /**
     * @param $table
     * @param $dateReportField
     * @param $hospitalId
     * @param $dateInput
     * @return array
     */
    private function statific($table, $dateReportField, $hospitalId, $dateInput)
    {
        $dateArr = [$dateInput => 0];
        $dateCompare = Carbon::parse($dateInput);

        for ($index = 0; $index < 6; $index++) {
            $dateArr = array_merge($dateArr, [$dateCompare->subDay()->format('yy-m-d') => 0]);
        }

        $countData = DB::table($table)
            ->select(DB::raw('count(*) as number_of_data, ' . $dateReportField))
            ->whereIn($dateReportField, array_keys($dateArr))
            ->where('hospitals_id', $hospitalId)
            ->groupBy($dateReportField)
            ->get();

        return array_merge($dateArr, $countData->pluck('number_of_data', $dateReportField)->toArray());
    }
}
