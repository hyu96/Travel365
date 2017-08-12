<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';

    const FOLLOW = 1;
    const UNFOLLOW = 0;
}
