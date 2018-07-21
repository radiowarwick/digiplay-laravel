<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallItem extends Model
{
    protected $table = 'aw_items';
    protected $primaryKey = 'id';
    public $timestamps = false;
}