@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/trips/edit.css') }}" rel="stylesheet">
@endsection

@section('body.content')
    <div class="container">
        <h1 class="text-center">Create Trip</h1>
        <hr>
        <div id="error">
                
        </div>
        <form action="#" method="POST" enctype="multipart/form-data"  id="trip-form">
            {{ csrf_field() }}
            <div class="row">
                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-md-4">
                    <input type="hidden" name="places" id="places" value="{{ $plans }}">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $trip->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="time_start">Time Start:</label>
                        <input type="text" class="form-control" id="time_start" name="time_start" value="{{ $trip->time_start }}" required>
                    </div>

                    <div class="form-group">
                        <label for="time_end">Time End:</label>
                        <input type="text" class="form-control" id="time_end" name="time_end" value="{{ $trip->time_end }}" readonly="readonly">
                    </div>

                    <div class="form-group">
                        <input type="button" name="submit" value="Finish Trip" class="btn btn-primary" id="submit">
                    </div>
                </div>
                <div class="col-md-7 col-md-offset-1">
                    <h2>Cover</h2>
                    <input type="file" id="cover_file" name="cover_file">
                    <img src="{{ asset($trip->cover) }}" id="cover-image" style="width: 650px">
                </div>
            </div>
        </form>
        <hr>
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
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                        </tr>
                    </thead>
                    <tbody id="plan-table">
                    </tbody>
                </table>
            </div>
            <div class="col-md-4 map">
                <input type="text" class="form-control" id="search-box">
                <div class="form-group" id="map">
                    <label for="map">Map</label>
                    <div id="map_canvas"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <h2 class="text-center">Planning</h2>
            <br>
            <div class="col-md-5 col-md-offset-1">
                <div class="form-group">
                    <label for="start">
                        <input type="radio" name="location" value="start" id="start-radio" checked> From
                    </label>
                    <textarea name="start" id="start" cols="62" rows="2" disabled></textarea>
                </div>

                <div class="form-group">
                    <label for="vehicle">Vehicle</label>
                    <input type="text" class="form-control" id="vehicle">
                </div>

                <div class="form-group">
                    <label for="activities">Activites</label>
                    <input type="text" class="form-control" id="activities">
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label for="end">
                        <input type="radio" name="location" value="end" id="end-radio"> To
                    </label>
                    <textarea name="end" id="end" cols="62" rows="2" disabled></textarea>
                </div>

                <div class="form-group">
                    <label for="plan_time_end">Time (hours)</label>
                    <input type="number" class="form-control" id="plan_time" min=1>
                </div>

                <div class="form-group col-sm-3" style="display: none;" id="stay-box">
                    <label for="stay">Stay</label><br>
                    <select name="stay" id="stay">
                        <option value= 0 selected="selected">No</option>
                        <option value= 1>Yes</option>  
                    </select>
                </div>

                <div class="form-group" style="display: none;" id="end-box">
                    <label for="end">End Here</label><br>
                    <select name="end-trip" id="end-trip">
                        <option value= 0>No</option>
                        <option value= 1>Yes</option>  
                    </select>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-1">
                <input type="button" name="add_button" value="Add Plan" class="btn btn-info" id="add-button">.
            </div>
        </div>
    </div>
@endsection

@section('body.js')
    <script src="{{ asset('/js/trips/edit.js') }}"></script> 
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsUPogq_PeMpW7RjS29odIP1to7wbS0Sk&libraries=places&callback=initMap">
    </script>
    <link href="{{ asset('/css/common/jquery.datetimepicker.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/common/jquery.datetimepicker.full.min.js') }}"></script>
@endsection