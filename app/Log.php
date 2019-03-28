<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    CONST LOCATIONS = [
        'SUE',
        'Studio 1',
        'Studio 2',
        'OB',
        'Testing'
    ];

	protected $table = 'log';
	protected $primaryKey = 'id';
    public $timestamps = false;

	public function user() {
		return $this->hasOne('App\User', 'id', 'userid');
	}

	public function audio() {
		return $this->hasOne('App\Audio', 'id', 'audioid');
	}

	public function location_verbose() {
		return Log::LOCATIONS[$this->location];
	}
}
