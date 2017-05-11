$("#fakultas").change(function() {
	var fakultas = $("#fakultas").val();
	$.ajax({
		url: '../models/search.php',
		type: 'POST',
		data: {
			fakultas: fakultas
		},
		success: function(res) {
			$("#jurusan").html(res);
		}
	});
	
});

// new 
// cek validasi input
// untuk mengganti title pada HTML 5
function checkValid(input) {
        if (input.value == "fun") {
            input.setCustomValidity("You're having too much fun!");
        } else {
            // input is fine -- reset the error message
            input.setCustomValidity('');
        }
}


$("#fupload").fileupload({
    dataType: 'json',
    add: function (e, data) {
        data.context = $('<p/>').text('Uploading...').appendTo(document.body);
        data.submit();
    },
    done: function (e, data) {
        data.context.text('Upload finished.');
    }
});