// ajax menggunakan jQuery

$('#cari').hide();

$('#keyword').on('keyup', function() {
	$('#container').load('cari.php?keyword=' + $('#keyword').val());
});