<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Join;
use App\Models\Follow;
use App\User;
use Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * return all join request to user's trip
     */
    public function noti() {
    	$join = User::find(Auth::id())->join_request;
    	$filter = $join->where('status', Join::REQUEST);
        $join_detail = array();
        foreach ($filter as $value) {
            array_push($join_detail, Join::with(['user', 'trip'])->find($value->id));
        }
        return view("users.noti",  [ 
            "requests" => $join_detail,
            "notiNum" => count($filter)
        ]);
    }

    /**
     * show user profile
     */
    public function show($id) {
        $user = User::find($id);
        
        $createdTrips = Trip::where('user_id', $id)->get();
        
        $joinedTrips = Join::with('trip')->get();
        $joinedTrips = $joinedTrips->where('user_id', $id);

        $followedTrips = Follow::with('trip')->get();
        $followedTrips = $followedTrips->where('user_id', $id);
        
        return view('users.profile', [
            'user' => $user,
            'createdTrips' => $createdTrips,
            'joinedTrips' => $joinedTrips,
            'followedTrips' => $followedTrips,
            'notiNum' => $this->notiNum()
        ]);
    }

    /**
     * edit user profile
     */
    public function edit($id) {
        $user = User::find($id);
        return view('users.edit', [
            'user' => $user,
            'notiNum' => $this->notiNum()
        ]);
    }

    /**
     * update user profilee
     */
    public function update(StoreUser $request) {
        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        if ($request->gender == User::MALE) {
            $user->gender = User::MALE;
        } else {
            $user->gender = User::FEMALE;
        }

        if($request->hasFile('avatar_file')) {
            if ($user->avatar != "avatars/default_avatar.png") {
                unlink($user->avatar);
            } 
            $path = "avatars/".$request->file('avatar_file')->hashName();
            $request->file('avatar_file')->move(public_path("/avatars"), $request->file('avatar_file')->hashName());
            $user->avatar = $path;
        }
        
        $user->birthday = $request->birthday;
        $user->save();
        return $this->show($user->id);
    }


    /**
     * get number of noti
     */
    public function notiNum() {
        if (Auth::check()) {
            $join = User::find(Auth::id())->join_request;
            $filter = $join->where('status', Join::REQUEST);
            return count($filter);
        } else {
            return 0;
        }
    }
}
