@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/users/profile.css') }}" rel="stylesheet">
@endsection

@section('body.content')
    <div class="container">
        <input type="hidden" value="{{ $user->id }}" id="user_id">
        <div class="col-md-2">
            <img src="{{ asset($user->avatar) }}" id="avatar">
            @if(Auth::id() == $user->id)
            <input type="button" value="Edit Profile" id="edit" class="btn btn-primary">
            @endif
        </div>  
        <div class="col-md-10 info">
            <p>Name: <b>{{ $user->name }}</b></p>
            <p>Gender:
                @if ($user->gender == 0)
                    <b>Male</b>
                @else
                    <b>Female</b>
                @endif
            </p> 
            <p>Birthday: <b>{{ $user->birthday }}</b></p>
            <p>Email: <b>{{ $user->email }}</b></p>
            <p>Phone: <b>{{ $user->phone }}</b></p>
        </div>
    </div>
    <br>
    <div class="container">
        <h3>Created Trips ({{ count($createdTrips) }})</h3>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="1">Trips</th>
                    <th colspan="1">Cover</th>
                    <th colspan="1">Time Start</th>
                    <th colspan="1">Time End</th>
                    <th colspan="1">Owner</th>
                    <th colspan="1">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($createdTrips as $trip)
                <tr>
                    <th colspan="1">
                        <a href="{{ route('trips.show',$trip->id) }}">{{ $trip->name }}</a>
                    </th>
                    <th colspan="1"><img src="{{ asset($trip->cover) }}" class="cover"></th>
                    <th colspan="1">{{ $trip->time_start }}</th>
                    <th colspan="1">{{ $trip->time_end }}</th>
                    <th colspan="1">
                        <a href="{{ route('users.profile', $trip->owner->id) }}">{{ $trip->owner->name }}</a>
                    </th>
                    <th colspan="1">
                        @if ($trip->status == 0) 
                            Cancel                          
                        @elseif ($trip->status == 1)
                            <a href="{{ route('trips.show',$trip->id) }}"> >> Join us</a>
                        @elseif ($trip->status == 2) 
                            Running
                        @else
                            Finish
                        @endif
                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br>

    <div class="container">
        <h3>Joined Trips ({{ count($joinedTrips) }})</h3>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="1">Trips</th>
                    <th colspan="1">Cover</th>
                    <th colspan="1">Time Start</th>
                    <th colspan="1">Time End</th>
                    <th colspan="1">Owner</th>
                    <th colspan="1">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($joinedTrips as $join)
                <tr>
                    <th colspan="1">
                        <a href="{{ route('trips.show',$join->trip->id) }}">{{ $join->trip->name }}</a>
                    </th>
                    <th colspan="1"><img src="{{ asset($join->trip->cover) }}" class="cover"></th>
                    <th colspan="1">{{ $join->trip->time_start }}</th>
                    <th colspan="1">{{ $join->trip->time_end }}</th>
                    <th colspan="1">
                        <a href="{{ route('users.profile', $join->trip->owner->id) }}">{{ $join->trip->owner->name }}</a>
                    </th>
                    <th colspan="1">
                        @if ($join->trip->status == 0) 
                            Cancel                          
                        @elseif ($join->trip->status == 1)
                            <a href="{{ route('trips.show',$join->trip->id) }}"> >> Join us</a>
                        @elseif ($join->trip->status == 2) 
                            Running
                        @else
                            Finish
                        @endif
                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br>

    <div class="container">
        <h3>Followed Trips ({{ count($followedTrips) }})</h3>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="1">Trips</th>
                    <th colspan="1">Cover</th>
                    <th colspan="1">Time Start</th>
                    <th colspan="1">Time End</th>
                    <th colspan="1">Owner</th>
                    <th colspan="1">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($followedTrips as $follow)
                <tr>
                    <th colspan="1">
                        <a href="{{ route('trips.show',$follow->trip->id) }}">{{ $follow->trip->name }}</a>
                    </th>
                    <th colspan="1"><img src="{{ asset($follow->trip->cover) }}" class="cover"></th>
                    <th colspan="1">{{ $follow->trip->time_start }}</th>
                    <th colspan="1">{{ $follow->trip->time_end }}</th>
                    <th colspan="1">
                        <a href="{{ route('users.profile', $follow->trip->owner->id) }}">{{ $follow->trip->owner->name }}</a>
                    </th>
                    <th colspan="1">
                        @if ($follow->trip->status == 0) 
                            Cancel                          
                        @elseif ($follow->trip->status == 1)
                            <a href="{{ route('trips.show',$follow->trip->id) }}"> >> Join us</a>
                        @elseif ($follow->trip->status == 2) 
                            Running
                        @else
                            Finish
                        @endif
                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/users/profile.js') }}"></script>
@endsection