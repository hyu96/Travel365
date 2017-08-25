if($("#gender").data('value') == 0) {
	$("input[name='gender'][value=0]").attr("checked", true);
} else {
	$("input[name='gender'][value=1]").attr("checked", true);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#avatar').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#avatar_file").change(function(){
    readURL(this);
});
