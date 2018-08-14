<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewAudio extends Model
{
    protected $table = 'v_audio';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function audio() {
    	return $this->hasOne('App\Audio', 'id', 'id');
    }
}
