<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudioLogin extends Model
{
    protected $table = 'studio_logins';
    protected $primaryKey = 'id';
    protected $dates = [
        'created_at',
        'updated_at',
        'logout_at',
    ];

    public function user()
    {
        return $this->hasOne('\App\User', 'username', 'username');
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('created_at', \Carbon\Carbon::parse($date));
    }
}
