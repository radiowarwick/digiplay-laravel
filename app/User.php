<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Adldap\Laravel\Facades\Adldap;

use App\Showplan;

class User extends Authenticatable
{
	use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getLdapAttribute($attribute) {
    	return Adldap::search()->where('uid', '=', $this->username)->get('items')->get(0)['attributes'][$attribute][0];
    }

    public function groups() {
    	return $this->hasManyThrough('App\Group', 'App\GroupUser', 'username', 'id', 'username', 'group_id');
    }

    public function hasPermission($permission) {
        $groups = $this->groups;
        foreach($groups as $group) {
            // Always allow if the user is an admin
            if($group->name === 'Admin')
                return true;
            // Otherwise check each of the user's groups for the correct permission
            else {
                foreach($group->permissions as $permission) {
                    if($permission->name === $permission_string)
                        return true;
                }
            }
        }
        return false;
    }

    public function audiowall() {
        $current_audiowall = UserConfig::where('userid', $this->id)->where('configid', 1)->first();
        if(is_null($current_audiowall))
            return 0;
        else
            return $current_audiowall->val;
    }

    public function showplans($studio = false) {
        $showplans = Showplan::all();
        $editable = [];
        foreach($showplans as $showplan) {
            if($showplan->canEdit($this, $studio))
                $editable[] = $showplan; 
        }
        return collect($editable);
    }
}
