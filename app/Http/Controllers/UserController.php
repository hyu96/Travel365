<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Join;
use App\Models\Follow;
use App\User;

class UserController extends Controller
{
    public function noti() {
    	$join = User::find(Auth::id())->join_request;
    	$filter = $join->where('status', 1);
        $join_detail = array();
        foreach ($filter as $value) {
            array_push($join_detail, Join::with(['user', 'trip'])->find($value->id));
        }
        return view("users.noti",  [ 
            "requests" => $join_detail,
            "notiNum" => count($filter)
        ]); //->with('requests', $join_detail);
    }
}
