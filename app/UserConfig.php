<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    protected $table = 'usersconfigs';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function scopeAudiowall($query)
    {
        return $query->where('configid', 1);
    }
}
