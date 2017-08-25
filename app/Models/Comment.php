<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';

	public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function trip()
    {
        return $this->belongsTo('App\Models\User','trip_id','id');
    }
}
