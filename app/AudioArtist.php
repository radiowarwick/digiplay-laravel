<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioArtist extends Model
{
    protected $table = 'audioartists';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
