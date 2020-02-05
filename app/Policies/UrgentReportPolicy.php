<?php

namespace App\Policies;

use App\User;
use App\models\UrgentReport;
use Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class UrgentReportPolicy
{
    use HandlesAuthorization;

    public function view(User $user){
        return $user->hasAccess(['urgent_reports.index']);
    }  
    public function show(User $user, UrgentReport $urgent_reports){
        return $user->hasAccess(['urgent_reports.index']) && $user->hospitals_id == $urgent_reports->hospitals_id;
    }
    public function create(User $user){
        return $user->hasAccess(['urgent_reports.create']);
    }
}
