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
					<th colspan="1">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($trips as $trip)
				<tr>
					<th colspan="1">
						<a href="{{ route('trips.show',$trip->id) }}">{{ $trip->name }}</a>
					</th>
					<th colspan="1"><img src="{{ asset($trip->cover) }}"></th>
					<th colspan="1">{{ $trip->time_start }}</th>
					<th colspan="1">{{ $trip->time_end }}</th>
					<th colspan="1">{{ $trip->owner->name }}</th>
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
	<div class="container">
		
	</div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/trips/index.js') }}"></script>
@endsection