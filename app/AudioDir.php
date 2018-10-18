<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioDir extends Model
{
	protected $table = 'audiodir';
	protected $primaryKey = 'id';
	public $timestamps = false;
}