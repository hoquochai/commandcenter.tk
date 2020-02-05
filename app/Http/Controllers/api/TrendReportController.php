<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

class TrendReportController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $user = Auth::user();
        $urgent_reports = UrgentReport::where('hospitals_id', $user->hospitals_id)->get();
    }
}
