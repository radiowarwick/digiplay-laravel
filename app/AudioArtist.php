<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioArtist extends Model
{
    protected $table = 'audioartists';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function artist()
    {
        return $this->hasOne('App\Artist', 'id', 'artistid');
    }

    public function audio()
    {
        return $this->hasOne('App\Audio', 'id', 'audioid');
    }
}
