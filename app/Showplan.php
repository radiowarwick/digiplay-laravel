<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showplan extends Model
{
	protected $table = 'new_showplans';
	protected $pirmaryKey = 'id';

	public function items() {
		return $this->hasMany('App\ShowplanItem', 'showplan_id', 'id')->orderBy('position');
	}

	public function reposition() {
		$i = 1;
		foreach($this->items as $item) {
			$item->position = $i++;
			$item->save();
		}
	}

	public function permissions() {
		return $this->hasMany('App\ShowplanPermission', 'showplan_id', 'id');
	}

	public function canEdit($user) {
		if($user->hasPermission('Showplan admin'))
			return true;

		foreach($this->permissions as $permission) {
			if($permission->user->username == $user->username)
				return true;
		}
		return false;
	}
}
