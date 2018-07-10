<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'new_groups';
    protected $primaryKey = 'id';

    public function users() {
    	return $this->hasManyThrough('App\User', 'App\GroupUser', 'group_id', 'username', 'id', 'username');
    }

    public function permissions() {
    	return $this->hasManyThrough('App\Permission', 'App\GroupPermission', 'group_id', 'id', 'id', 'permission_id');
    }
}
