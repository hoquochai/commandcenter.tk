<?php

namespace App\Policies;
use App\models\Complain;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplainPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user){
        return $user->hasAccess(['complains.index']);
    }  
    public function show(User $user){
        return $user->hasAccess(['complains.show']);
    }
    public function create(User $user){
        return $user->hasAccess(['complains.create']);
    }
    public function edit(User $user){
        return $user->hasAccess(['complains.edit']);
    }
    public function update(User $user){
        return $user->hasAccess(['complains.update']);
    }
    public function delete(User $user){
        return $user->hasAccess(['complains.delete']);
    }
}
