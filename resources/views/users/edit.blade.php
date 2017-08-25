@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/users/edit.css') }}" rel="stylesheet">
@endsection

@section('body.content')
    <div class="container">
        <h1 class="text-center">Edit Profile</h1>
        <hr>
        <form action="{{ route('users.update') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
            <input type="hidden" value="{{ $user->id }}" id="user_id" name="user_id">
            <div class="col-md-3 col-md-offset-1">
                @if ($errors->has('avatar_file'))
                    <span class="help-block">
                        <strong>{{ $errors->first('avatar_file') }}</strong>
                    </span>
                @endif
                <h2>Cover</h2>
                <input type="file" id="avatar_file" name="avatar_file">
                <img src="{{ asset($user->avatar) }}" id="avatar">
                <input type="submit" value="Edit Profile" class="btn btn-primary" id="submit">
            </div>
            <div class="col-md-8 info">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('Phone') ? ' has-error' : '' }}">
                    <label for="phone" class="col-md-4 control-label">Phone Number</label>

                    <div class="col-md-6">
                        <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>

                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                    <label for="gender" class="col-md-4 control-label" id="gender" data-value="{{ $user->gender }}">
                        Gender
                    </label>
                    <div class="col-md-6">
                        <label class="radio-inline"><input type="radio" name="gender" value="0">Male</label>
                        <label class="radio-inline"><input type="radio" name="gender" value="1">Female</label>

                        @if ($errors->has('gender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                    <label for="birthday" class="col-md-4 control-label">Birthday</label>

                    <div class="col-md-6">
                        <input id="birthday" type="date" class="form-control" name="birthday" 
                            value="{{ $user->birthday }}" required>

                        @if ($errors->has('birthday'))
                            <span class="help-block">
                                <strong>{{ $errors->first('birthday') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </form> 
    </div>
@endsection

@section('body.js')
	<script src="{{ asset('/js/users/edit.js') }}"></script>
	
@endsection