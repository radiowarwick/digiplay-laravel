<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Adldap\Laravel\Facades\Adldap;

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
        return \App\Http\Middleware\Permission::hasPermission($permission);
    }

    public function audiowall() {
        $current_audiowall = UserConfig::where('userid', $this->id)->where('configid', 1)->first();
        if(is_null($current_audiowall))
            return -1;
        else
            return $current_audiowall->val;
    }
}
