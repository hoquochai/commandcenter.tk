<?php

namespace App\Http\Controllers\api;

use App\User;
use Carbon\Carbon;
use App\models\TrendReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrendReportController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $hospitalId = $user->hospitals_id;
            $dateInputUrgentReport = $this->handleRequest($request, 'date_urgent_report');
            $dateInputAssaultedStaff = $this->handleRequest($request, 'date_assaulted_staff');
            $dateInputComplain = $this->handleRequest($request, 'date_complain');
            $dateInputLaborAccident = $this->handleRequest($request, 'date_labor_accident');
            $output = $this->handleOutput($hospitalId, $dateInputUrgentReport, $dateInputAssaultedStaff, $dateInputComplain, $dateInputLaborAccident);

            return response()->json(['data'=> $output], $this->successStatus);
        } catch (\Exception $exception) {
            return response()->json(['data'=> 'Get data failure'], $this->exceptionStatus);
        }
    }

    /**
     * @param $hospitalId
     * @param $dateInputUrgentReport
     * @param $dateInputAssaultedStaff
     * @param $dateInputComplain
     * @param $dateInputLaborAccident
     * @return mixed
     */
    private function handleOutput($hospitalId, $dateInputUrgentReport, $dateInputAssaultedStaff, $dateInputComplain, $dateInputLaborAccident)
    {
        $output['bao_cao_su_co_y_khoa_nghiem_trong'] = $this->statific('urgent_reports', 'date_report', $hospitalId, $dateInputUrgentReport);
        $output['bao_cao_nhan_vien_y_te_bi_hanh_hung'] = $this->statific('assaulted', 'date_assaulted', $hospitalId, $dateInputAssaultedStaff);
        $output['bao_cao_khieu_nai_khieu_kien'] = $this->statific('complains', 'date_complain', $hospitalId, $dateInputComplain);
        $output['bao_cao_tai_nan_lao_dong'] = $this->statific('labor_accidents', 'Date_report', $hospitalId, $dateInputLaborAccident);

        return $output;
    }

    /**
     * @param Request $request
     * @param $dateField
     * @return mixed|string
     */
    private function handleRequest(Request $request, $dateField)
    {
        return $request->has($dateField) ? $request->get($dateField) : Carbon::now()->format('yy-m-d');
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->isRole(User::ROLE_DIRECTOR)) {
                return response()->json(['data'=> 'User does not have permission to access'], $this->permissionStatus);
            }

            $hospitalId = $user->hospitals_id;
            $userReceiveId = $request->get('received_id');

            if ($userReceiveId) {
                $userReceive = User::where('id', $userReceiveId)->first();

                if (!$userReceive) {
                    return response()->json(['data'=> 'User not found'], $this->exceptionStatus);
                }

                $dateInputUrgentReport = $this->handleRequest($request, 'date_urgent_report');
                $dateInputAssaultedStaff = $this->handleRequest($request, 'date_assaulted_staff');
                $dateInputComplain = $this->handleRequest($request, 'date_complain');
                $dateInputLaborAccident = $this->handleRequest($request, 'date_labor_accident');
                $output = $this->handleOutput($hospitalId, $dateInputUrgentReport, $dateInputAssaultedStaff, $dateInputComplain, $dateInputLaborAccident);
                TrendReport::create([
                    'date_trend_reports' => Carbon::now()->format('yy-m-d'),
                    'date_urgent_report' => $dateInputUrgentReport,
                    'date_assaulted_staff' => $dateInputAssaultedStaff,
                    'date_complain' => $dateInputComplain,
                    'date_labor_accident' => $dateInputLaborAccident,
                    'users_id' => $user->id,
                    'received_id' => $userReceiveId,
                    'result' => json_encode($output),
                ]);

                return response()->json(['data'=> 'Created successfully'], $this->successStatus);
            }

            return response()->json(['data'=> 'The data is invalid'], $this->validationStatus);
        } catch (\Exception $exception) {
            return response()->json(['data'=> 'Send report failure'], $this->exceptionStatus);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = Auth::user();

            if (!$user->isRole(User::ROLE_DIRECTOR)) {
                return response()->json(['data'=> 'User does not have permission to access'], $this->permissionStatus);
            }

            $trendReport = TrendReport::with('user', 'receiver')->where('id', $id)->first();
            $trendReport->result = json_decode($trendReport->result);

            return response()->json(['data'=> $trendReport], $this->successStatus);
        } catch (\Exception $exception) {
            return response()->json(['data'=> 'Show report failure'], $this->exceptionStatus);
        }
    }
}
