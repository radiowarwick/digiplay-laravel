<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallWall extends Model
{
    protected $table = 'aw_walls';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('App\AudiowallItem', 'wall_id')->orderby('item', 'ASC');
    }
}
