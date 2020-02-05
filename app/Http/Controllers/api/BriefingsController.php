<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\models\HISDepartment;

class BriefingsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $user = Auth::user();
        $hisDepartments = HISDepartment::with('hisPatientHistories')->get();

    }
}
