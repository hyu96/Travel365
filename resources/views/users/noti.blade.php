@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/users/noti.css') }}" rel="stylesheet">
@endsectionoll

@section('body.content')
    <div class="container">
        <h2 class="text-center">Join Request</h2>
        <hr>
        @foreach ($requests as $request)
            <div data-id="{{ $request->id }}" class="request">
                <div class="row">
                    <div class="col-md-offset-2 col-md-6">
                        <img src="{{ asset($request->user->avatar) }}" class="avatar">
                        <a href="{{ route('users.profile', $request->user->id) }}">
                            <b>{{ $request->user->name }}</b>
                        </a> want to join your 
                        <a href="{{ route('trips.show',$request->trip->id) }}"><b>{{ $request->trip->name }}</b></a> trip
                    </div>
                    <div class="col-md-4">
                        <input type="button" value="Accept" class="btn btn-primary accept" data-id="{{ $request->id }}">
                        <input type="button" value="Refuse" class="btn btn-danger refuse" data-id="{{ $request->id }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/users/noti.js') }}"></script>
	
@endsection