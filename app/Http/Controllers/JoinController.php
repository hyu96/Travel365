<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Join;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Follow;
use App\User;

class JoinController extends Controller
{
	/**
     * store join request
     */
    public function join(Request $request) {
        $join = new Join;
        $join->user_id = $request->user_id;
        $join->trip_id = $request->trip_id;
        $join->status = Join::REQUEST; 
        $join->save();
        return "success";
    }

    /**
     * cancel join request
     */
    public function cancel(Request $request) {
    	$join = Join::where('user_id', $request->user_id)->where('trip_id', $request->trip_id)->delete();
    	return "canceled";
    }

    /**
     * accept join request
     */
    public function accept(Request $request) {
    	$join = Join::find($request->id);
    	$join->status = Join::ACCEPT;
    	$join->save();
    	return "accepted";
    }

    /**
     * refuse join request
     */
    public function refuse(Request $request) {
    	$join = Join::find($request->id);
    	$join->delete();
    	return "deleted";
    }

    /**
     * out trip
     */
    public function outTrip(Request $request) {
    	$join = Join::where('user_id', $request->user_id)->where('trip_id', $request->trip_id)->delete();
    	return "out";
    }

    public function kick(Request $request) {
        $join = Join::where('user_id', $request->user_id)->where('trip_id', $request->trip_id)->delete();
        return "kicked";
    }
}
