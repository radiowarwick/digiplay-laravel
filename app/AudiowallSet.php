<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallSet extends Model
{
    protected $table = 'aw_sets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function canView(User $user) {
    	return false;
    }
}
