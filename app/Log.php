<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	protected $table = 'log';
	protected $primaryKey = 'id';
    public $timestamps = false;

	public function user() {
		return $this->hasOne('App\User', 'id', 'userid');
	}

	public function audio() {
		return $this->hasOne('App\Audio', 'id', 'audioid');
	}
}
