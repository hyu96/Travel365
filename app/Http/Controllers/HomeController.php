<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Trip;
use App\Models\Join;
use App\Models\Follow;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->with('notiNum', $this->notiNum());
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
