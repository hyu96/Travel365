var map;
var geocoder;
var index = 1;
var places = [];
var marker_start;
var marker_end;

function initMap() {
    // create map
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        center: {lat: 21.0245, lng: 105.84117},
        zoom: 8
    });
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    directionsDisplay.setMap(map);

    //marker
    geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function(event) {
        var input = $("input:checked").val();
        placeMarker(event.latLng, input);
    }); 

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
            calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
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

function getAddress(latLng, input) {
    geocoder.geocode( {'latLng': latLng},
    function (results, status) {
        console.log(status);
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

function calculateAndDisplayRoute(directionsService, directionsDisplay,marker_start, marker_end) {
    var waypts = [];
    var stay = $("#stay").val();
    marker_start.setVisible(false);
    marker_end.setVisible(false);
    
    if (index == 1) {
        places[0] = {
            lat : marker_start.getPosition().lat(),
            lng : marker_start.getPosition().lng(),
            /*time : $("#plan_time_start").val(),*/
            name : $("#start").val()
        };
        places[1] = {
            lat : marker_end.getPosition().lat(),
            lng : marker_end.getPosition().lng(),
            name : $("#end").val(),
            /*time : $("#plan_time_end").val(),
            vehicle : $("#vehicle").val(),*/
            activities : "moving"
        };
    } else {
        places[index] = {
            lat : marker_end.getPosition().lat(),
            lng : marker_end.getPosition().lng(),
            name : $("#end").val()
            /*time : $("plan_time_end").val()*/
        };
        places[index].stay = stay;
        if (stay == 1) {
            places[index].activities = $("#activites").val();
            places[index].vehicle = "";
        } else {
            places[index].vehicle = $("vehicle").val();
            places[index].activities = "moving";
        }
    }
    
    var index_waypts = 0;
    for(i = 1; i < places.length; i++) {
        if (places[i].stay == 1) {
            continue;
            console.log(1);
        }
        waypts[index_waypts] = {
            location : new google.maps.LatLng(places[i].lat, places[i].lng),
            stopover : true
        }
        index_waypts++;
    }
    directionsService.route({
        origin: new google.maps.LatLng(places[0].lat, places[0].lng),
        destination: new google.maps.LatLng(places[0].lat, places[0].lng),
        waypoints: waypts,
        optimizeWaypoints: false,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } 
    });
}

function customizeMenu() {
    $("#start-radio").prop("disabled", true);
    $("#start").val($("#end").val());
    $("#end").val("");
    $("#stay").val(0);
    $("#end-radio").prop("checked", true);
    $("#combo-box").css("display", "block");
    $("#plan_time_start").prop("disabled", true);
    $("#plan_time_start").val($("#plan_time_end").val());
    $("#plan_time_end").val("");
    $("#vehicle").val("");
    $("activites").val("");
    $("#vehicle").prop("disabled", false);
    $("#activites").prop("disabled", true);
}

function changeTableInfo() {
    console.log(places);
    console.log(index);
    var html = $("#plan-table").html();
    html += "<tr>";
    html += "<th colspan='1'>" + places[index-1].name + "</th>";
    html += "<th colspan='1'>" + places[index].name + "</th>";
    html += "<th colspan='1'>" + places[index-1].time + "</th>";
    html += "<th colspan='1'>" + places[index].time + "</th>";
    html += "<th colspan='1'>" + places[index].vehicle + "</th>";
    html += "<th colspan='1'>" + places[index].activities + "</th>";
    html += "</tr>";
    $("#plan-table").html(html);

}

function addPlan() {
    if (!$("#start").val() || !$("#end").val()) {
        alert("Please select location");
        return false;
    }

    //calculateAndDisplayRoute(directionsService, directionsDisplay, marker_start, marker_end);
    if (index == 1) {
        places[0].time = $("#plan_time_start").val().toLocaleString();
        places[1].time = $("#plan_time_end").val().toLocaleString();
        places[1].vehicle = $("#vehicle").val();
        places[1].activities = "moving";
    } else {
        places[index].time = $("#plan_time_end").val().toLocaleString();
        places[index].stay = stay;
        if (stay == 1) {
            places[index].activities = $("#activities").val();
            places[index].vehicle = "Stay";
        } else {
            places[index].vehicle = $("#vehicle").val();
            places[index].activities = "moving";
        }
    }


    changeTableInfo();
    index++;
    marker_start.setPosition(marker_end.getPosition());
    customizeMenu();
}

$("#add-button").on("click",addPlan);

//stay here
$("#stay").change(function() {
    var stay = $("#stay").val();
    if (stay == 1) {
        marker_end.setPosition(marker_start.getPosition());
        $("#end").val($("#start").val());
        $("#vehicle").val("");
        $("#vehicle").prop("disabled", true);
        $("#activites").prop("disabled", false);
    } else {
        $("#end").val("");
        $("#activites").val("");
        $("#activites").prop("disabled", true);
        $("#vehicle").prop("disabled", false);
    }
});

$('#create-navbar').addClass('active');
