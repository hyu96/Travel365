<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Join;
use App\Models\Follow;
use App\User;

class FollowController extends Controller
{
	/**
     * store follow request 
     */
    public function follow(Request $request) {
    	if (Follow::where('user_id', $request->user_id)->where('trip_id', $request->trip_id)->count() == 0) {
	        $follow = new Follow;
	        $follow->user_id = $request->user_id;
	        $follow->trip_id = $request->trip_id;
	        $follow->save();
	    }
        return "success";
    }

    /**
     * delete follow request
     */
    public function unfollow(Request $request) {
        $join = Follow::where('user_id', $request->user_id)->where('trip_id', $request->trip_id)->delete();
        return "success";
    }

}
