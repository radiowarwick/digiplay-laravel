<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallSet extends Model
{
    protected $table = 'aw_sets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function hasView(User $user) {
    	return $this->userHasLevel($user, 1);
    }

    public function hasEdit(User $user) {
    	return $this->userHasLevel($user, 2);
    }

    public function hasAdmin(User $user) {
    	return $this->userHasLevel($user, 3);
    }

    /*
    * Levels are:
    *   1   View
    *   2   Edit
    *   3   Admin
    */
    public function userHasLevel(User $user, $level) {
    	if($user->hasPermission("Audiowall admin"))
    		return true;

    	foreach($this->permissions as $permission) {
    		if($user->username == $permission->username and $permission->level >= $level)
    			return true;
    	}
    	return false;
    }

    public function permissions() {
    	return $this->hasMany('App\AudiowallSetPermission', 'set_id');
    }

    public function walls() {
        return $this->hasMany('App\AudiowallWall', 'set_id')->orderby('page', 'ASC');
    }
}
