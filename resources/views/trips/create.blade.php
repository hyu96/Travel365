@extends('layouts.master')

@section('head.title')
    Travel365
@endsection

@section('head.css')
    <link href="{{ asset('/css/trips/create.css') }}" rel="stylesheet">
@endsection

@section('body.content')
    <div class="container">
        <h1 class="text-center">Create Trip</h1>
        <hr>
        <form action="#" method="POST">
            <div class="row">
                <div class="col-md-4">
                    <h2>Trip Information</h2>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="form-group">
                        <label for="time_start">Time Start:</label>
                        <input type="datetime-local" class="form-control" id="time_start">
                    </div>

                    <div class="form-group">
                        <label for="time_end">Time End:</label>
                        <input type="datetime-local" class="form-control" id="time_end">
                    </div>
                </div>
                <div class="col-md-7 col-md-offset-1">
                    <h2>Cover</h2>
                    <img src="#" alt="Image Cover">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <h2>Trip Detail</h2>
                    <table class="table-bordered tableSection">
                        <thead>
                            <tr>
                                <th colspan="1"><span class="text">From</span></th>
                                <th colspan="1"><span class="text">To</span></th>
                                <th colspan="1"><span class="text">Time Start</span></th>
                                <th colspan="1"><span class="text">Time End</span></th>
                                <th colspan="1"><span class="text">Vehicle</span></th>
                                <th colspan="1"><span class="text">Activities</span></th>
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
                        <label for="time_start">Time Start</label>
                        <input type="datetime-local" class="form-control" id="plan_time_start" name="plan_time_start">
                    </div>
                    <div class="form-group">
                        <label for="vehicle">Vehicle</label>
                        <input type="text" class="form-control" id="vehicle">
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
                        <label for="plan_time_end">Time End</label>
                        <input type="datetime-local" class="form-control" id="plan_time_end">
                    </div>
                    <div class="form-group">
                        <label for="activities">Activites</label>
                        <input type="text" class="form-control" id="activites" disabled>
                    </div>
                </div>
                <div class="col-md-1" style="display: none;" id="combo-box">
                    <div class="form-group">
                        <label for="stay">Stay</label>
                        <select name="stay" id="stay">
                            <option value= 0>No</option>
                            <option value= 1>Yes</option>  
                        </select>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-2">
                    <input type="button" name="add_button" value="Add Plan" class="btn btn-info" id="add-button">
                </div>
                <div class="col-md-2">
                    <input type="submit" name="submit" value="Finish Trip" class="btn btn-primary" id="submit">
                </div>
            </div>
        </form>
    </div>
@endsection

@section('body.js')
    <script src="{{ asset('/js/trips/create.js') }}"></script> 
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsUPogq_PeMpW7RjS29odIP1to7wbS0Sk&libraries=places&callback=initMap">
    </script>
@endsection