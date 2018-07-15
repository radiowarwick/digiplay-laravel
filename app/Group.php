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

    public function group_permissions() {
    	return $this->hasMany('App\GroupPermission', 'group_id', 'id');
    }

    public function has_permission($permission_name) {
    	foreach($this->permissions as $permission) {
    		if($permission->name == $permission_name)
    			return true;
    	}
    	return false;
    }
}
