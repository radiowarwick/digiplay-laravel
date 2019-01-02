<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';

    public function groups()
    {
        return $this->hasManyThrough('App\Group', 'App\GroupPermission', 'permission_id', 'id', 'id', 'group_id');
    }
}
