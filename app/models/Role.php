<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    protected $table = "roles";
    protected $fillable = [
        'name', 'slug', 'permission',
    ];
    protected $casts = [
        'permission' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_users', "users_id", "roles_id");

    }

    public function hasAccess(array $permission) : bool
    {
        foreach ($permission as $per) {
            if ($this->hasPermission($per))
                return true;
        }
        return false;
    }

    private function hasPermission(string $per) : bool
    {
        return $this->permission[$per] ?? false;
    }
}
