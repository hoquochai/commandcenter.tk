<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Auth;
use Mail;
use App\models\ReportType;
use App\models\Department;
use App\models\UrgentReports;
use App\models\AccountType;
use App\models\SeriousProblemType;
class TrendReportController extends Controller
{
    public function index(){

    }
    public function create(){

    }
    public function store(Request $request){
    	$start = $request->date_report;
    	$time = Carbon::parse($start)->format('M d Y');
    	var_dump($time); exit();
    }
}
