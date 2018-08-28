<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'email';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function scopeLatest($query) {
    	return $query->orderBy('id', 'DESC')->limit(25);
    }
}
