<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Join;
use App\User;

class Trip extends Model
{
	protected $table = 'trips';

	/**
	 * const for trip status
	 */
	const CANCEL = 0;
    const PLANNING = 1;
    const RUNNING = 2;
    const FINISH = 3;

    /**
     * const for permission to this trip
     */
    const GUESS = 0;
    const OWNER = 1;

    /**
     * Get the owner of this trip.
     */
    public function owner()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    /**
     * Get all plans of this trip.
     */
    public function plan()
    {
        return $this->hasMany('App\Models\Plan');
    }

    public function join()
    {
        return $this->hasMany('App\Models\Join', 'trip_id');
    }
}
