<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prerecord extends Model
{
    public function audio() {
		return $this->hasOne('App\Audio', 'id', 'audio_id');
	}
}
