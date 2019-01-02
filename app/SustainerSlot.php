<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SustainerSlot extends Model
{
    protected $table = 'sustslots';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function audio()
    {
        return $this->hasOne('App\Audio', 'id', 'audioid');
    }

    public function playlist()
    {
        return $this->hasOne('App\Playlist', 'id', 'playlistid');
    }
}
