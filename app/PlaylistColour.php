<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistColour extends Model
{
    protected $table = 'playlistcolours';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function foreground()
    {
        $red = hexdec(substr($this->colour, 0, 2));
        $green = hexdec(substr($this->colour, 2, 2));
        $blue = hexdec(substr($this->colour, 4, 2));

        if (($red * 0.299 + $green * 0.587 + $blue * 0.114) > 186) {
            return '000';
        }

        return 'fff';
    }
}
