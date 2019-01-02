<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallSetPermission extends Model
{
    protected $table = 'aw_set_permissions';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne('App\User', 'username', 'username');
    }
}
