<?php

namespace App\Http\Controllers\api;

use App\User;
use App\models\Briefing;
use App\models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BriefingsController extends Controller
{
    public $successStatus = 200;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $hospitalId = $user->hospitals_id;

        if ($user->isRole(User::ROLE_DIRECTOR)) {
            $briefings = $this->getListBriefings($request, $hospitalId);

            foreach ($briefings as $briefing) {
                $briefing->frequence = $briefing->getFrequence();
            }

            return response()->json(['data'=> $briefings], $this->successStatus);
        }

        $departments = $this->getDepartments($request);
        return response()->json(['data'=> $departments], $this->successStatus);
    }

    /**
     * @param Request $request
     * @param $hospitalsId
     * @return mixed
     */
    private function getListBriefings(Request $request, $hospitalsId)
    {
        $searchData = $request->only(['key_word', 'from_date', 'to_date', 'frequence', 'report_types']);
        $query = Briefing::with('user', 'receiver', 'hospital', 'reportType')
            ->where('hospitals_id', $hospitalsId);

        if ($request->has('pageSize')) {
            $limit = $request->get('pageSize');
        } else {
            $limit = config('settings.limit_pagination');
        }

        $briefings = $this->handleSearch($searchData, $query, 'date_briefings')
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        foreach ($briefings as $briefing) {
            $briefing->result = json_decode($briefing->result);
        }

        return $briefings;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getDepartments(Request $request)
    {
        $fromDate = null;
        $toDate = null;

        if ($request->has('from_date')) {
            $fromDate = Carbon::parse($request->get('from_date'))->toDateTimeString();
        }
        if ($request->has('to_date')) {
            $toDate = Carbon::parse($request->get('to_date'))->toDateTimeString();
        }

        $departments = Department::withCount([
            'bn_ngoai_tru_cu' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_cu_trong_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_vao_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_ra_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_chuyen_den'=> function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_chuyen_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_ngoai_tru_chuyen_khoa' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },

            'bn_noi_tru_cu' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_cu_trong_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_vao_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_ra_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_chuyen_den'=> function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_chuyen_vien' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
            'bn_noi_tru_chuyen_khoa' => function ($query) use($fromDate, $toDate) {
                $query->searchByDate($fromDate, $toDate);
            },
        ])->get();

        foreach ($departments as $department) {
            $department->bn_ngoai_tru_hien_co_count = ($department->bn_ngoai_tru_cu_trong_vien_count + $department->bn_ngoai_tru_vao_vien_count) . '/' . ($department->bn_ngoai_tru_cu_trong_vien_count + $department->bn_ngoai_tru_ra_vien_count + $department->bn_ngoai_tru_vao_vien_count);
            $department->bn_noi_tru_hien_co_count = $department->bn_noi_tru_cu_trong_vien_count + $department->bn_noi_tru_vao_vien_count . '/' . ($department->bn_noi_tru_cu_trong_vien_count + $department->bn_noi_tru_ra_vien_count + $department->bn_noi_tru_vao_vien_count);
        }

        return $departments;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
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

            $output = $this->getDepartments($request);
            Briefing::create([
                'date_briefings' => $request->has('date_briefings') ? $request->get('date_briefings') : Carbon::now()->format('yy-m-d'),
                'users_id' => $user->id,
                'received_id' => $userReceiveId,
                'result' => json_encode($output),
                'title' => $request->get('title'),
                'frequence' => $request->get('frequence'),
                'hospitals_id' => $hospitalId,
                'report_types_id' => $request->get('report_types_id'),
                'from_date' => $request->has('from_date') ? $request->get('from_date') : null,
                'to_date' => $request->has('to_date') ? $request->get('to_date') : null,
            ]);

            return response()->json(['data'=> 'Created successfully'], $this->successStatus);
        }

        return response()->json(['data'=> 'The data is invalid'], $this->validationStatus);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        $user = Auth::user();

        if (!$user->isRole(User::ROLE_DIRECTOR)) {
            return response()->json(['data'=> 'User does not have permission to access'], $this->permissionStatus);
        }

        $briefing = Briefing::with('user', 'receiver', 'hospital', 'reportType')->where('id', $id)->first();
        $briefing->result = json_decode($briefing->result);
        $briefing->frequence = $briefing->getFrequence();

        if ($request->has('from_date') || $request->has('to_date')) {
            $output = $this->getDepartments($request);
            $briefing->result = $output;
        }

        return response()->json(['data'=> $briefing], $this->successStatus);
    }
}
