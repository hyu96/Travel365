var map;
var places = [];
var directionsService;
var directionsDisplay;

function initMap() {
    // create map
    map = new google.maps.Map(document.getElementById('map_canvas'), {
        center: {lat: 21.0245, lng: 105.84117},
        zoom: 8
    });
    places = JSON.parse($("#plans").val());
    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;
    calculateAndDisplayRoute(directionsService, directionsDisplay);
    directionsDisplay.setMap(map);
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});

//store marker in array places and display route in screen
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    var waypts = [];
    
    //take waypoints from array places
    var index_waypts = 0;
    for(i = 1; i < places.length - 1; i++) {
        if (places[i].stay == 1) {
            continue;
        }
        waypts[index_waypts] = {
            location : new google.maps.LatLng(places[i].place_lat, places[i].place_lng),
            stopover : true
        }
        index_waypts++;
    }
    //console.log(waypts);

    directionsService.route({
        origin: new google.maps.LatLng(places[0].place_lat, places[0].place_lng),
        destination: new google.maps.LatLng(places[places.length - 1].place_lat, places[places.length - 1].place_lng),
        waypoints: waypts,
        optimizeWaypoints: false,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } 
    });
}

//handle follow trip button click event
$("#follow").on('click', follow);

function follow() {
    var dataFollow = {
        "trip_id" : $("#trip_id").val(),
        "user_id" : $("#user_id").val()
    };

    console.log(dataFollow);
    $.ajax({
        url: '/follow',
        type: "post",
        dataType: "text",
        data: dataFollow,
        success: function(data){
            var html = "<input type='button' value='Unfollow' class='btn btn-info' id='unfollow'>";
            $("#follow").replaceWith(html);
            $("#unfollow").on('click', unfollow);
        },
        error: function(data) {
            console.log(0);
        }
    });
}

//handle follow trip button click event
$("#unfollow").on('click', unfollow);

function unfollow() {
    var dataFollow = {
        "trip_id" : $("#trip_id").val(),
        "user_id" : $("#user_id").val()
    };

    console.log(dataFollow);
    $.ajax({
        url: '/unfollow',
        type: "post",
        dataType: "text",
        data: dataFollow,
        success: function(data){
            var html = "<input type='button' value='Follow' class='btn btn-info' id='follow'>";
            $("#unfollow").replaceWith(html);
            $("#follow").on('click', follow);
        },
        error: function(data) {
            console.log(0);
        }
    });
}

 
//handle join trip button click event
$("#join").on('click', join);

function join() {
    follow();
    var data = {
        "user_id" : $("#user_id").val(),
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/join',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data){
            var html = "<input type='button' value='Cancel request' class='btn btn-danger' id='cancel_join'>"
            $("#join").replaceWith(html);
            $("#cancel_join").on('click',cancelJoin);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle cancel request join trip click event
$("#cancel_join").on('click',cancelJoin);

function cancelJoin() {
    var data = {
        "user_id" : $("#user_id").val(),
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/join/cancel',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data){
            var html = "<input type='button' value='Join Trip' class='btn btn-primary' id='join'>"
            $("#cancel_join").replaceWith(html);
            $("#join").on('click',join);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle cancel request join trip click event
$("#out_trip").on('click',outTrip);

function outTrip() {
    var data = {
        "user_id" : $("#user_id").val(),
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/join/outTrip',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data) {
            var html = "<input type='button' value='Join Trip' class='btn btn-primary' id='join'>"
            $("#out_trip").replaceWith(html);
            $("#join").on('click',join);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle start trip click event
$("#start").on('click',startTrip);

function startTrip() {
    var data = {
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/trips/start',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data) {
            $("#div-button").html("<input type='button' value='Finish Trip' class='btn btn-primary' id='finish'>");
            $("#status").html("Status: <b>Running</b>");
            $("#finish").on('click', finishTrip);
            $(".kick").remove();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle cancel trip click event
$("#cancel").on('click',cancelTrip);

function cancelTrip() {
    var data = {
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/trips/cancel',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data) {
            $("#div-button").remove();
            $("#status").html("Status: <b>Cancel</b>");
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle finish trip event
$("#finish").on('click', finishTrip);

function finishTrip() {
    var data = {
        "trip_id" : $("#trip_id").val()
    }
    
    $.ajax({
        url: '/trips/finish',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data) {
            $("#div-button").remove();
            $("#status").html("Status: <b>Finish</b>");
        },
        error: function(data) {
            console.log(data);
        }
    });
}

//handle follow trip button click event
$(".kick").on('click', kick);

function kick() {
    var id = $(this).data("user_id");
    var data = {
        "trip_id" : $("#trip_id").val(),
        "user_id" : id
    };
    
    $.ajax({
        url: '/join/kick',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data){
            $("div[data-id='" + id +"']").remove();
        },
        error: function(data) {
            console.log(0);
        }
    });
}


//handle edit trip button click event
$("#edit").on('click', function(){
    window.location.href = "/trips/" + $("#trip_id").val() + "/edit";
});

//press enter on comment box
$('#comment_box').on('keypress', function (e) {
    if(e.which === 13){
        if ($("#cmt_content").val() == "") {
            return false;
        }

        var dataComment = {
            "trip_id" : $("#trip_id").val(),
            "user_id" : $("#user_id").val(),
            "content" : $("#cmt_content").val()
        };

        $.ajax({
            url: '/comment/store',
            type: "post",
            dataType: "text",
            data: dataComment,
            success: function(data){
                console.log(data);
            },
            error: function(data) {
                console.log(0);
            }
        });
    }
});

$( document ).ready(function() {
    var socket = io.connect('http://127.0.0.1:8890');
    console.log('connected..');
    socket.on('message', function(data) {
        data = JSON.parse(data);
        console.log(data);
        var html = "";
        html += "<div class='row user_comment' data_user_id =" + data[0].id + " >";
        html += "<div class='col-md-offset-2 col-md-1'>";
        html += "<img src='/" + data[0].user.avatar + "' class='avatar'>";
        html += "</div>";
        html += "<div class='col-md-1'>";
        html += "<a href='{{ route('users.profile', " + data[0].user.id  + " }}'>" + data[0].user.name + "</a>";
        html += "</div>";
        html += "<div class='col-md-8'>";              
        html += "<div>";
        html += data[0].content;
        html += "</div>";
        html += "<div>";
        if ($("#user_id").length != 0) {
            html += "<span id='reply'>Reply</span>";
        }
        html += "</div>";
        html += "</div>"; 
        $("#comment_show").append(html);
        $("#cmt_content").val("");
    });
});

$(".reply").on('click', function(){
    var id = $(this).parent().parent().parent().parent().data('user_id');
    var sub = $(".sub_comment_box").find("[data-id=" + id + "]");
    console.log(sub);
});