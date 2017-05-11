var huruf = $('#film'),
	button = $('#pilih'),
	container = $('#container'),
	result = $('.result'),
	details = $('.details'),
	loader = $('.loader');

button.hide();

huruf.on('change', function(e) {
	e.preventDefault();
	container.load('daftar_film.php', { huruf: huruf.val() });
});

function tampilDeskripsi(id) {
	details.fadeIn('fast');
	loader.show();
	result.html('');
	result.load(
		'detail_film.php?id=' + id + ' .result',
		function() {
			loader.hide();
		}
	);
}

$('.close').click(function() {
	details.fadeOut('fast');
});



// ISENG

// jika area berwarna abu2 transparan di klik, hilangkan modal
$('.details').click(function() {
	details.fadeOut('fast');
});

// jika area modal (yang putih) di klik, jangan hilangkan modal
$('.content').click(function(e) {
	e.stopPropagation();
});

// jika tombol esc di klik, hilangkan modal
$(document).keyup(function(e) {
  if (e.keyCode == 27) { $('.close').click(); }
});