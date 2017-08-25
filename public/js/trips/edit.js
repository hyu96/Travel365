var map;
var geocoder;
var index = 1;
var places = [];
var marker_start;
var marker_end;
var directionsService;
var directionsDisplay;
var edit = false;
var change_start = false;

function initMap() {
    // create map
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        center: {lat: 21.0245, lng: 105.84117},
        zoom: 8
    });
    places = JSON.parse($("#places").val());
    index = places.length;
    places.forEach(function(place) {
        place.lat = place.place_lat;
        delete place.place_lat;    
        place.lng = place.place_lng;
        delete place.place_lng; 
        place.name = place.place_name;
        delete place.place_name; 
    });
    customizeMenu();
    $("#start").val(places[places.length-1].name);
    changeTableInfo();

    marker_start = new google.maps.Marker({ 
        position: new google.maps.LatLng(places[0].lat, places[0].lng), 
        map: map,
    });

    marker_end = new google.maps.Marker({ 
        position: new google.maps.LatLng(places[places.length-1].lat, places[places.length-1].lng), 
        map: map,
    });

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;
    calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end); 
    directionsDisplay.setMap(map);

    //handle google map click event 
    geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function(event) {
        var input = $("input:checked").val();
        placeMarker(event.latLng, input);
    }); 

    //display marker when click on google map
    function placeMarker(location, input) {
        if (input == "start") {
            if (marker_start) {
                marker_start.setPosition(location);
            } else {
                marker_start = new google.maps.Marker({ 
                    position: location, 
                    map: map,
                });
            }
            getAddress(location, input);
        } else {
            if (marker_end) {
                marker_end.setPosition(location); 
            } else {
                marker_end = new google.maps.Marker({ 
                    position: location, 
                    map: map,
                });
            }
            getAddress(location, input);
        }
        
        if (marker_start && marker_end) {
            setTimeout(function(){ 
                storePlace(marker_start, marker_end);
                calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end); 
            }, 500);
        }
    }
    
    //search box
    var input = document.getElementById('search-box');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    //end search box 
}

//get marker address and display it in screen
function getAddress(latLng, input) {
    geocoder.geocode( {'latLng': latLng},
    function (results, status) {
        var box = $('#' + input);
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                box.val(results[0].formatted_address);
            } else {
                box.val("No results");
            }
        } else {
            box.val(status);
        }
    });
}

//store marker in array places and display route in screen
function calculateAndDisplayRoute(directionsService, directionsDisplay,marker_start, marker_end) {
    var waypts = [];
    var stay = $("#stay").val();
    marker_start.setVisible(false);
    marker_end.setVisible(false);

    //take waypoints from array places
    var index_waypts = 0;
    for(i = 1; i < places.length - 1; i++) {
        if (places[i].stay == 1) {
            continue;
        }
        waypts[index_waypts] = {
            location : new google.maps.LatLng(places[i].lat, places[i].lng),
            stopover : true
        }
        index_waypts++;
    }

    directionsService.route({
        origin: new google.maps.LatLng(places[0].lat, places[0].lng),
        destination: new google.maps.LatLng(places[places.length - 1].lat, places[places.length - 1].lng),
        waypoints: waypts,
        optimizeWaypoints: false,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } 
    });
}

function storePlace(marker_start, marker_end) {
    if (index == 1 && edit == false) {
        places[0] = {
            'index' : 0,
            'lat' : marker_start.getPosition().lat(),
            'lng' : marker_start.getPosition().lng(),
            'name' : $("#start").val(),
            'stay' : 0
        };
    }
    
    places[index] = {
        'index' : index,
        'lat' : marker_end.getPosition().lat(),
        'lng' : marker_end.getPosition().lng(),
        'name' : $("#end").val(),
        'stay' : 0
    };
}

// change menu properties when add new plan
function customizeMenu() {
    $("#start-radio").prop("disabled", true);
    $("#end").val("");
    $("#stay").val(0);
    $("#end-radio").prop("checked", true);
    $("#stay-box").css("display", "block");
    $("#end-box").css("display", "block");
    $("#vehicle").val("");
    $("#activities").val("");
    $("#plan_time").val(null);
}

// add new plan's information to plan-table 
function changeTableInfo() {
    var html = "";
    for (i = 1; i < places.length; i++){ 
        html += "<tr id=" + i + ">";
        html += "<th colspan='1' class='from'>" + places[i-1].name + "</th>";
        html += "<th colspan='1' class='to'>";
        html += "<span class='to_location'>" + places[i].name + "</span>";
        html += "</th>";
        html += "<th colspan='1' class='time'>" + places[i].time + "</th>";
        html += "<th colspan='1' class='vehicle'>" + places[i].vehicle + "</th>";
        html += "<th colspan='1' class='activities'>" + places[i].activities + "</th>";
        html += "<th colspan='1' class='edit'><input type='button' class='edit-btn btn btn-primary' value='Edit'></th>";
        html += "<th colspan='1' class='delete'><input type='button' class='delete-btn btn btn-danger' value='Delete'></th>";
        html += "</tr>";
    }
    $("#plan-table").html(html);
    $(".delete-btn").on("click",deletePlan);
    $(".edit-btn").on("click",editPlan);
}


//handle stay combo-box change value event
$("#stay").change(function() {
    var stay = $("#stay").val();
    if (stay == 1) {
        $('#end-trip').val(0);
        marker_end.setPosition(marker_start.getPosition());
        $("#end").val($("#start").val());
        $("#vehicle").val("");
        $("#end-radio").prop("disabled", true);
        $("#end-radio").prop("checked", false);
        storePlace(marker_start, marker_end);
        calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
    } else {
        $("#end").val("");
        $("#activities").val("");
        $("#end-radio").prop("checked", true);
    }
});

//handle end here combo-box change value event
$("#end-trip").change(function() {
    var end = $("#end-trip").val();
    if (end == 1) {
        $('#stay').val(0);
        marker_end.setPosition(
            new google.maps.LatLng(places[0].lat, places[0].lng)
        );
        $("#end").val(places[0].name);
        $("#vehicle").val("");
        $("#end-radio").prop("disabled", true);
        $("#end-radio").prop("checked", false);
        storePlace(marker_start, marker_end);
        calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
    } else {
        $("#end").val("");
        $("#activities").val("");
        $("#end-radio").prop("checked", true);
    }
});

//handle Add Plan button click event
function addPlan() {
    if (!$("#start").val() || !$("#end").val()) {
        alert("Please select location");
        return false;
    }
    var stay = $("#stay").val();
    places[index].time = $("#plan_time").val();
    places[index].stay = stay;
    places[index].activities = $("#activities").val();
    places[index].vehicle = $("#vehicle").val();
    $("#start").val(places[places.length - 1].name);
    changeTableInfo();
    index++;
    marker_start.setPosition(marker_end.getPosition());
    customizeMenu();
    caculateTime();
    console.log(places);
}

$("#add-button").on("click",addPlan);

//handle Edit Plan button click event
function editPlan() {
    edit = true;
    customizeMenu();
    var id = $(this).parent().parent().attr('id');
    places[id].stay = 0;
    var toId = "to" + id;
    var temp = index;
    index = id;
    var html = "";
    html += "<input type='radio' name='location' style='display: none' value='" + toId + "' checked>";
    html += "<textarea cols='10' rows='6' id='" + toId + "' disabled></textarea>"; 
    $("#" + id).children(".to").html(html);
    $("#" + id).children(".time").html("<input type='number' min='0' />");
    $("#" + id).children(".vehicle").html("<input />");
    $("#" + id).children(".activities").html("<input />");
    if (id == 1) {
        $("#" + id).children(".edit").html("<th colspan='1'><input type='button' class='change-start-btn btn btn-info' value='Change Start'></th>");
    } else {
        $("#" + id).children(".edit").html("<th colspan='1'><input type='button' class='stay-btn btn btn-info' value='Stay'></th>");
    }
    $("#" + id).children(".delete").html("<th colspan='1'><input type='button' class='confirm-btn btn btn-primary' value='Confirm'></th>")
    
    $(".confirm-btn").on("click", function() {
        if (change_start) {
            places[0].name = $("#1").children(".from").children("textarea").val();
        } else {
            places[id].name = $("#" + toId).val();
        }
        places[id].time = $("#" + id).children(".time").children("input").val();
        places[id].vehicle = $("#" + id).children(".vehicle").children("input").val();
        places[id].activities = $("#" + id).children(".activities").children("input").val();
        index = temp;
        changeTableInfo();
        customizeMenu();
        edit = false;
        change_start = false;
        $("#start").val(places[places.length - 1].name);
        calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
        caculateTime();
    });

    $(".stay-btn").on("click", function() {
        places[id].stay = 1;
        $("#" + toId).val(places[id-1].name);
        places[id].name = places[id-1].name;
        places[id].lat = places[id-1].lat;
        places[id].lng = places[id-1].lng;
        calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
    })

    $(".change-start-btn").on("click", function() {
        change_start = true;
        index = 0;
        var html = "";
        html += "<input type='radio' name='location' style='display: none' value='change-start' checked>";
        html += "<textarea cols='10' rows='6' id='change-start' disabled></textarea>"; 
        $("#1").children(".from").html(html);         
        $("#1").children(".to").html(places[1].name);                              
    })
}   

//handle delete button click event
function deletePlan() {
    customizeMenu();
    var id = $(this).parent().parent().attr('id');
    if (id === $("#plan-table tr:last").attr('id')) {
        if (id == 1) {
            console.log(1);
            places = [];
            $("#start-radio").prop("disabled", false);
            $("#start-radio").prop("checked", true);
            $("#start").val("");
            $("#stay-box").css("display", "none");
            $("#end-box").css("display", "none");
        } else {
            places.splice(id,1);
            customizeMenu();
        }
        index--;
        changeTableInfo();
    } else {
        places.splice(id,1);
        index--;
        if (places[id].stay == 1) {
            places.splice(id,1);
            index--;
        }
        changeTableInfo();
        calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
        $("#" + id).children(".time").html("<input type='number' min='0' />");
        $("#" + id).children(".vehicle").html("<input />");
        $("#" + id).children(".activities").html("<input />");
        $("#" + id).children(".edit").html("");
        $("#" + id).children(".delete").html("<th colspan='1'><input type='button' class='confirm-btn btn btn-info' value='Confirm'></th>")

        $(".confirm-btn").on("click", function() {
            places[id].time = $("#" + id).children(".time").children("input").val();
            places[id].vehicle = $("#" + id).children(".vehicle").children("input").val();
            places[id].activities = $("#" + id).children(".activities").children("input").val();
            changeTableInfo();
            customizeMenu();
            caculateTime();
        });
    }
}

//customize 
$('#create-navbar').addClass('active');

//upload cover
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#cover-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#cover_file").change(function(){
    readURL(this);
});

//submit form
$('#submit').on('click', function() {
    $("#places").val(JSON.stringify(places));
    var data = new FormData(); 
    data.append('name', $("#name").val());
    data.append('time_start', $("#time_start").val());
    data.append('time_end', $("#time_end").val());
    data.append('places', places);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    console.log(new FormData($("#trip-form")[0]));
    $.ajax({
        url: '/trips/update',
        type: "post",
        dataType: "text",
        async : false,
        processData: false,
        contentType : false,
        data: new FormData($("#trip-form")[0]),
        success: function(data){
            window.location.href = "/";
        },
        error: function(data) {
            var errors = JSON.parse(data.responseText);
            var html = "";
            html += "<div class='alert alert-danger'>";
            html += "<strong>Whoops!</strong> There were some problems with your input.<br><br>";
            html += "<ul>";
            $.each(errors, function(index, error) {
                html += "<li>";
                html += error[0];
                html += "</li>";
            });
            html += "</ul>";
            html += "</div>";
            $("#error").html(html);
        }
    });
});

$(document).ready(function() {
    $('#time_start').datetimepicker({
        onChangeDateTime:function(dp,$input){
            caculateTime();
        }
    });
});

function caculateTime() {
    var time = 0;
    for(var i = 1; i < places.length; i++) {
        if(places[i].time) {
            time += parseInt(places[i].time);
        }
    }

    var startdate = $('#time_start').datetimepicker('getValue');
    var fromDate = new Date(startdate);
    fromDate = new Date(fromDate.setHours(fromDate.getHours()+ time));
        
    var day = formatNum(fromDate.getDate());
    var month = formatNum(fromDate.getMonth()+1);
    var year = fromDate.getFullYear();
    var hour = formatNum(fromDate.getHours());
    var minute = formatNum(fromDate.getMinutes());
    var seconds = formatNum(fromDate.getSeconds());
    var toDate = year + '/' + month + '/' + day + ' ' + hour + ':' + minute;
    $('#time_end').val(toDate);
}

function formatNum(num) {   
    return num < 10 ? '0' + num : '' + num;
} 
