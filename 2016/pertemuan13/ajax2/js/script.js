$('#cari').on('keyup', function() {
	$('.container').load('helpers/cari.php?cari=' + $('#cari').val());
});