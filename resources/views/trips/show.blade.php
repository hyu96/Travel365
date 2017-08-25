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
			<h4>Owner: <a href="{{ route('users.profile', $trip->owner->id) }}"><b>{{ $trip->owner->name }}</b></a></h4>
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
                <div class="col-md-8">
                    <h4 class="text-center">Plan Discuss</h4>
                    <div class="comment-layout">
                    <div class="show-comment" id="show-comment">
                        @foreach($comments as $comment)
                            @if($comment->parent_id == null)
                            <div class="comment">
                                <input type="hidden" class="comment_id" value="{{$comment->id}}" >
                                <div class="avatar-user" style="background-image: url(/{{$comment->user->avatar}})"></div>
                                <div class="right">
                                    <div class="comment-and-name">
                                        <a href="#" class="user-name">
                                            <p>{{$comment->user->name}}</p>
                                        </a>
                                        <p> {{$comment->content}}</p>
                                    </div>
                                    <div class="show-sub-comment">
                                        <button id="btn-{{$comment->id}}" class="btn-link" style="float:left; font-size: 13px">sub comment</button>
                                    </div>
                                    <div id="sub-comment-layout-{{$comment->id}}" style="display: none">
                                        <div id="list-sub-comment-{{$comment->id}}">
                                            @foreach($comments as $sub_comment)
                                                @if($sub_comment->parent_id == $comment->id)
                                                    <div class="sub-comment">
                                                            <div class="avatar-user" style=" background-image: url(/{{$sub_comment->user->avatar}})"></div>
                                                            <div class="content-comment">
                                                                <div class="">
                                                                    <a href="#" class="" class="user-name">
                                                                        <p>{{$sub_comment->user->name}}</p>
                                                                    </a>
                                                                    <p> {{$sub_comment->content}}</p>
                                                                </div>
                                                            </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="input-sub-comment" style="margin-left: 70px;">
                                            <div class="avatar-user" style="background-image: url(/{{ $sub_comment->user->avatar }})" ></div>
                                            <div class="form-group" style="width: 100%; display: inline-block; margin-top: 10px">
                                                <input type="text" class="form-control" id="input-sub-comment-{{$comment->id}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="input-comment">
                            <a href="a">
                                <?php
                                    $link_avatar_user = asset(Auth::User()->avatar)
                                ?>
                                <div class="avatar-user" style="background-image: url({{$link_avatar_user}})"></div>
                            </a>
                            <div class="form-group" style="margin-top: 10px; display: inline-block; width: 930px">
                                <input type="email" class="form-control" id="input-comment">
                            </div>
                    </div>
                </div>


                <div class="col-md-4 member-list">
                    <h4 class="text-center">Joined Members</h4>
                    @if ($permission['owner'] == 1 and $trip->status == 1)
                        @foreach ($members as $member)
                        <div class="member" data-id="{{ $member->user->id }}">
                            <div class="row">
                                <div class="col-md-5 col-md-offset-2">    
                                    <img src="{{ asset($member->user->avatar) }}" class="avatar">
                                    <a href="{{ route('users.profile', $member->user->id ) }}">{{ $member->user->name }}</a>
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
                            <img src="{{ asset($member->user->avatar) }}" class="avatar">
                            <a href="{{ route('users.profile', $member->user->id) }}">{{ $member->user->name }}</a>
                        </div>
                        @endforeach
                    @endif
                </div>
            
        </div>
	</div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/trips/show.js') }}"></script>
    <script src="{{ asset('/js/common/socket.io.js') }}"></script>
	<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsUPogq_PeMpW7RjS29odIP1to7wbS0Sk&libraries=places&callback=initMap">
    </script>
@endsection