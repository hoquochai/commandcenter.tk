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
}
