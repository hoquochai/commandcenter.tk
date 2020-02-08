<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $successStatus = 200;

    public $exceptionStatus = 404;

    public $permissionStatus = 403;

    public $validationStatus = 412;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function handleSearch($searchData, $query, $dateReportField)
    {
        if (isset($searchData['key_word'])) {
            $query->where('title', 'LIKE', '%' .$searchData['key_word'] . '%');
        }
        if (isset($searchData['from_date'])) {
            $query->where($dateReportField, '>=', $searchData['from_date']);
        }
        if (isset($searchData['to_date'])) {
            $query->where($dateReportField, '<=', $searchData['to_date']);
        }
        if (isset($searchData['frequence'])) {
            $query->where('frequence', $searchData['frequence']);
        }
        if (isset($searchData['report_types'])) {
            $query->where('report_types_id', $searchData['report_types']);
        }

        return $query;
    }
}
