$("#noti-navbar").addClass('active');

$(".accept").on('click', function() {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

	var id = $(this).data('id');
    var notiNum = parseInt($("#notify").text());
    var data = {
    	"id" : $(this).data('id')
    }

    $.ajax({
        url: '/join/accept',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data){
            $("div[data-id='" + id +"']").remove();
            $("#notify").text(notiNum - 1);
        },
        error: function(data) {
            console.log(data);
        }
    });
});

$(".refuse").on('click', function() {
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

	var id = $(this).data('id');
    var notiNum = parseInt($("#notify").text());
    var data = {
    	"id" : $(this).data('id')
    }

    $.ajax({
        url: '/join/refuse',
        type: "post",
        dataType: "text",
        data: data,
        success: function(data){
            $("div[data-id='" + id +"']").remove();
            $("#notify").text(notiNum - 1);
        },
        error: function(data) {
            console.log(data);
        }
    });
});
