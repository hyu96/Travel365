<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StorePlan;
use App\Http\Requests\StoreTrip;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Join;
use App\Models\Follow;
use App\User;

class TripController extends Controller
{
    /**
     * bind auth middleware 
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trips = Trip::with('owner')->get();
        return view("trips.index", [
            "trips" => $trips,
            "notiNum" => $this->notiNum()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trips.create')->with("notiNum", $this->notiNum());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrip $request)
    {
        $places = json_decode($request['places']);
        $trip = new Trip;
        $trip->user_id = Auth::id();
        $trip->name = $request->name;
        $trip->time_start = $request->time_start;
        $trip->time_end = $request->time_end;
        $trip->place_lat = $places[0]->lat;
        $trip->place_lng = $places[0]->lng;
        $trip->place_name = $places[0]->name;
        $trip->status = Trip::PLANNING;
        $path = "covers/".$request->file('cover_file')->hashName();
        $request->file('cover_file')->move(public_path("/covers"), $request->file('cover_file')->hashName());
        $trip->cover = $path;
        $trip->save();
        foreach ($places as $place) {
            $plan = new Plan;
            $plan->trip_id = $trip->id;
            $plan->index = $place->index;
            $plan->place_lat = $place->lat;
            $plan->place_lng = $place->lng;
            $plan->place_name = $place->name;
            $plan->stay = $place->stay;
            if ($place->index > 0) {
                $plan->time = $place->time;
                $plan->vehicle = $place->vehicle;
                $plan->activities = $place->activities;                
            }
            $plan->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trip = Trip::with('owner')->find($id);
        $plans = Trip::find($id)->plan;
        $members = Join::with('user')->where('trip_id', $id)->where('status', 2)->get();
        $permission = $this->checkpermission($id);
        return view("trips.show", [ 
            "trip" => $trip,
            "plans" => $plans,
            "permission" => $permission,
            "members" => $members,
            "notiNum" => $this->notiNum()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trip = Trip::find($id);
        $plans = $trip->plan;
        return view('trips.edit', [
            'trip' => $trip,
            'plans' => $plans,
            'notiNum' => $this->notiNum()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    /**
     * start Trip
     */
    public function startTrip(Request $request) {
        $trip = Trip::find($request->trip_id);
        $trip->status = Trip::RUNNING;
        $trip->save();
        return "started";
    }

    /**
     * cancel Trip
     */
    public function cancelTrip(Request $request) {
        $trip = Trip::find($request->trip_id);
        $trip->status = Trip::CANCEL;
        $trip->save();
        return "canceled";
    }

    /**
     * finish trip
     */
    public function finishTrip(Request $request) {
        $trip = Trip::find($request->trip_id);
        $trip->status = Trip::FINISH;
        $trip->save();
        return "finish";
    }

    /**
     *  check permission of current user to this trip 
     */
    public function checkPermission($trip_id) {
        $trip = Trip::find($trip_id);
        $permission = array();
        if (Auth::id() == $trip->user_id) {
            $permission = [
                'owner' => Trip::OWNER,
            ];
        } else {
            $permission = [
                'owner' => Trip::GUESS
            ];

            if (Follow::where('user_id', Auth::id())->where('trip_id', $trip_id)->count() == 1) {
                $permission['follow'] = Follow::FOLLOW;
            } else {
                $permission['follow'] = Follow::UNFOLLOW;
            }
            
            $join = Join::where('user_id', Auth::id())->where('trip_id', $trip_id)->get();
            if($join->count() == 0) {
                $permission['join'] = Join::NOT_JOIN;
            } else {
                if ($join[0]->status == 1) {
                    $permission['join'] = Join::REQUEST; 
                } else {
                    $permission['join'] = Join::ACCEPT;
                }
            }
        }
        return $permission;
    }

    /**
     * get number of noti
     */
    public function notiNum() {
        if (Auth::check()) {
            $join = User::find(Auth::id())->join_request;
            $filter = $join->where('status', 1);
            return count($filter);
        } else {
            return 0;
        }
    }
}
