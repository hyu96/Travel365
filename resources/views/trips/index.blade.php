@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/trips/index.css') }}" rel="stylesheet">
@endsection

@section('body.content')
	<div class="container">
		<h3>New Trips</h3>
		<table class="table">
			<thead>
				<tr>
					<th colspan="1">Trips</th>
					<th colspan="1">Cover</th>
					<th colspan="1">Time Start</th>
					<th colspan="1">Time End</th>
					<th colspan="1">Owner</th>
					<th colspan="1">Follow</th>
					<th colspan="1">Join</th>
					<th colspan="1">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($newTrips as $trip)
				<tr>
					<th colspan="1">
						<a href="{{ route('trips.show',$trip->id) }}">{{ $trip->name }}</a>
					</th>
					<th colspan="1"><img src="{{ asset($trip->cover) }}"></th>
					<th colspan="1">{{ $trip->time_start }}</th>
					<th colspan="1">{{ $trip->time_end }}</th>
					<th colspan="1"><a href="{{ route('users.profile', $trip->owner->id)}}">{{ $trip->owner->name }}</a></th>
					<th colspan="1" class="text-center">{{ $trip->followNum }}</th>
					<th colspan="1" class="text-center">{{ $trip->joinNum }}</th>
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
		<h3>Hot Trips</h3>
		<table class="table">
			<thead>
				<tr>
					<th colspan="1">Trips</th>
					<th colspan="1">Cover</th>
					<th colspan="1">Time Start</th>
					<th colspan="1">Time End</th>
					<th colspan="1">Owner</th>
					<th colspan="1">Follow</th>
					<th colspan="1">Join</th>
					<th colspan="1">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($hotTrips as $trip)
				<tr>
					<th colspan="1">
						<a href="{{ route('trips.show',$trip->id) }}">{{ $trip->name }}</a>
					</th>
					<th colspan="1"><img src="{{ asset($trip->cover) }}"></th>
					<th colspan="1">{{ $trip->time_start }}</th>
					<th colspan="1">{{ $trip->time_end }}</th>
					<th colspan="1"><a href="{{ route('users.profile', $trip->owner->id)}}">{{ $trip->owner->name }}</a></th>
					<th colspan="1" class="text-center">{{ $trip->followNum }}</th>
					<th colspan="1" class="text-center">{{ $trip->joinNum }}</th>
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
		<h3>All Trips</h3>
		<table class="table">
			<thead>
				<tr>
					<th colspan="1">Trips</th>
					<th colspan="1">Cover</th>
					<th colspan="1">Time Start</th>
					<th colspan="1">Time End</th>
					<th colspan="1">Owner</th>
					<th colspan="1">Follow</th>
					<th colspan="1">Join</th>
					<th colspan="1">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($allTrips as $trip)
				<tr>
					<th colspan="1">
						<a href="{{ route('trips.show',$trip->id) }}">{{ $trip->name }}</a>
					</th>
					<th colspan="1"><img src="{{ asset($trip->cover) }}"></th>
					<th colspan="1">{{ $trip->time_start }}</th>
					<th colspan="1">{{ $trip->time_end }}</th>
					<th colspan="1"><a href="{{ route('users.profile', $trip->owner->id)}}">{{ $trip->owner->name }}</a></th>
					<th colspan="1" class="text-center">{{ $trip->followNum }}</th>
					<th colspan="1" class="text-center">{{ $trip->joinNum }}</th>
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
@endsection

@section('body.js')
	<script src="{{ asset('/js/trips/index.js') }}"></script>
@endsection