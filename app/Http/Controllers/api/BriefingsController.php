<?php

namespace App\Http\Controllers\api;

use App\User;
use App\models\Briefing;
use App\models\Department;
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

        $departments = $this->getDepartments();
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

    private function getDepartments()
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

            $output = $this->getDepartments();
            Briefing::create([
                'date_briefings' => $request->has('date_briefings') ? $request->get('date_briefings') : Carbon::now()->format('yy-m-d'),
                'users_id' => $user->id,
                'received_id' => $userReceiveId,
                'result' => json_encode($output),
                'title' => $request->get('title'),
                'frequence' => $request->get('frequence'),
                'hospitals_id' => $hospitalId,
                'report_types_id' => $request->get('report_types_id'),
            ]);

            return response()->json(['data'=> 'Created successfully'], $this->successStatus);
        }

        return response()->json(['data'=> 'The data is invalid'], $this->validationStatus);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user->isRole(User::ROLE_DIRECTOR)) {
            return response()->json(['data'=> 'User does not have permission to access'], $this->permissionStatus);
        }

        $briefing = Briefing::with('user', 'receiver', 'hospital', 'reportType')->where('id', $id)->first();
        $briefing->result = json_decode($briefing->result);
        $briefing->frequence = $briefing->getFrequence();

        return response()->json(['data'=> $briefing], $this->successStatus);
    }
}
