var huruf = $('#film'),
	button = $('#pilih'),
	container = $('#container');

button.hide();

huruf.on('change', function(e) {
	e.preventDefault();
	container.load('daftar_film.php', { huruf: huruf.val() });
});

function tampilDeskripsi(id) {
	container.load('detail_film.php?id=' + id);
}