<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallSetPermission extends Model
{
    protected $table = 'aw_set_permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
