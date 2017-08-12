<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Join extends Model
{
    protected $table = 'joins';

    const NOT_JOIN = 0;
    const REQUEST = 1;
    const ACCEPT = 2;

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function trip()
    {
        return $this->belongsTo('App\Models\Trip','trip_id','id');
    }
}
