<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Trip;

class User extends Authenticatable
{
    const MALE = 0;
    const FEMALE = 1;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'birthday', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all trip this user owned.
     */
    public function trip()
    {
        return $this->hasMany('App\Models\Trip');
    }

    public function join_request()
    {
        return $this->hasManyThrough(
            'App\Models\Join', 'App\Models\Trip',
            'user_id', 'trip_id', 'id'
        );
    }
}
