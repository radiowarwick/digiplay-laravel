<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowplanItem extends Model
{
	protected $table = 'new_showplan_items';
	protected $pirmaryKey = 'id';

	public function audio() {
		return $this->hasOne('App\Audio', 'id', 'audio_id');
	}

	public function showplan() {
		return $this->hasOne('App\Showplan', 'id', 'showplan_id');
	}
}
