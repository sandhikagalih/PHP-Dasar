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