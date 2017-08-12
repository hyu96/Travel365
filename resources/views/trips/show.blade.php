@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/trips/show.css') }}" rel="stylesheet">
@endsection

@section('body.content')
	<input type="hidden" id="plans" value="{{ $plans }}">
    <input type="hidden" id="trip_id" value="{{ $trip->id }}">
	<div class="container">
		<div class="col-md-4">
			<img src="{{ asset($trip->cover) }}">
		</div>
		<div class="col-md-8">
			<h2>{{ $trip->name }}</h2>
			<h4>Owner: <b>{{ $trip->owner->name }}</b></h4>
			<h4>From: <b>{{ $trip->time_start }}</b></h4>
			<h4>To: <b>{{ $trip->time_end }}</b></h4>
            <h4 id="status">Status: 
            @if ($trip->status == 0) 
                <b>Cancel</b>
            @elseif ($trip->status == 1)
                <b>Planning</b>
            @elseif ($trip->status == 2)
                <b>Running</b>
            @else
                <b>Finish</b>
            @endif
            </h3>
            @if (Auth::check())
                <input type="hidden" id="user_id" value="{{ Auth::id() }}">
                @if ($permission['owner'] == 1) 
                    @if($trip->status == 1)
                    <div id="div-button">
                        <input type="button" value="Edit Trip" class="btn btn-info" id="edit">
                        <input type="button" value="Start Trip" class="btn btn-primary" id="start">
                        <input type="button" value="Cancel Trip" class="btn btn-danger" id="cancel">
                    </div>
                    @elseif ($trip->status == 2)
                    <div id="div-button">
                        <input type="button" value="Finish Trip" class="btn btn-primary" id="finish">
                    </div>
                    @endif
                @elseif ($permission['owner'] == 0 and $trip->status == 1) 
                    @if ($permission['follow'] == 1)
                        <input type="button" value="Unfollow" class="btn btn-info" id="unfollow">
                    @else
                        <input type="button" value="Follow" class="btn btn-info" id="follow">
                    @endif

                    @if ($permission['join'] == 0)
                        <input type="button" value="Join Trip" class="btn btn-primary" id="join">
                    @elseif ($permission['join'] == 1)
                        <input type='button' value='Cancel request' class='btn btn-danger' id='cancel_join'>
                    @else 
                        <input type='button' value='Out Trip' class='btn btn-danger' id='out_trip'>
                    @endif
                @endif
            @endif
		</div>
	</div>
	<hr>
	<div class="container plan_detail">
        <div class="row">
            <div class="col-md-8">
                <h2>Trip Detail</h2>
                <table class="table-bordered tableSection">
                    <thead>
                        <tr>
                            <th colspan="1"><span class="text">From</span></th>
                            <th colspan="1"><span class="text">To</span></th>
                            <th colspan="1"><span class="text">Time (hours)</span></th>
                            <th colspan="1"><span class="text">Vehicle</span></th>
                            <th colspan="1"><span class="text">Activities</span></th>
                        </tr>
                    </thead>
                    <tbody id="plan-table">
                    	@for ($i = 1; $i < count($plans); $i++)
						<tr>
							<th colspan="1"><span class="text">{{ $plans[$i - 1]->place_name }}</span></th>
							<th colspan="1"><span class="text">{{ $plans[$i]->place_name }}</span></th>
							<th colspan="1"><span class="text">{{ $plans[$i]->time }}</span></th>
							<th colspan="1"><span class="text">{{ $plans[$i]->vehicle }}</span></th>
							<th colspan="1"><span class="text">{{ $plans[$i]->activities }}</span></th>
						</tr>
                    	@endfor
                    </tbody>
                </table>
            </div>
            <div class="col-md-4 map">
                <div class="form-group" id="map">
                    <label for="map">Map</label>
                    <div id="map_canvas"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="text-center">Plan Discuss</h4>
                </div>
                <div class="col-md-4 member-list">
                    <h4 class="text-center">Joined Members</h4>
                    @if ($permission['owner'] == 1)
                        @foreach ($members as $member)
                        <div class="member" data-id="{{ $member->user->id }}">
                            <div class="row">
                                <div class="col-md-5 col-md-offset-2">    
                                    <img src="{{ asset('/avatars/default_avatar.png') }}" class="avatar">
                                    {{ $member->user->name }}
                                </div>
                                <div class="col-md-1">
                                    <input type="button" class="btn btn-danger kick" data-user_id="{{ $member->user->id }}" value="Kick">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        @foreach ($members as $member)
                        <div class="member">
                            <img src="{{ asset('/avatars/default_avatar.png') }}" class="avatar">
                            {{ $member->user->name }}
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
	</div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/trips/show.js') }}"></script>
	<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsUPogq_PeMpW7RjS29odIP1to7wbS0Sk&libraries=places&callback=initMap">
    </script>
@endsection