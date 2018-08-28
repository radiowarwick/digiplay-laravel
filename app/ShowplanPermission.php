<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowplanPermission extends Model
{
	protected $table = 'showplan_permissions';
	protected $primaryKey = 'id';

	public function showplan() {
		return $this->hasOne('App\Showplan', 'id', 'showplan_id');
	}

	public function user() {
		return $this->hasOne('App\User', 'username', 'username');
	}
}
