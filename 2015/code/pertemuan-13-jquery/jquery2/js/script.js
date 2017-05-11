var form = $('#form-komentar');

// ketika form disubmit
form.submit(function(e) {
	// caching
	var nama = $('#nama'),
		pesan = $('#komentar'),
		loading = $('#loading'),
		daftarKomentar = $('#daftar-komentar');

	// memunculkan gambar loading sebelum data dari ajax sukses diterima
	loading.show();

	// mengunakan $.ajax
	$.ajax({
		url: 'simpan_komentar.php',
		type: 'POST',
		data: { nama: nama.val(), komentar: pesan.val() },
		success: function(hasil) {
			// tampilkan data hasil ajax
			daftarKomentar.html(hasil);
			// sembunyikan lagi gambar loading
			loading.hide();
			// bersihkan kembali textfield
			nama.val('');
			pesan.val('');
		}
	});
	
	// menggunakan $.post
	// $.post(
	// 	'simpan_komentar.php',
	// 	{ nama: nama.val(), komentar: pesan.val() },
	// 	function(hasil) {
	// 		daftarKomentar.html(hasil);
	// 		loading.hide();
	// 		nama.val('');
	// 		pesan.val('');
	// 	}
	// );

	// menggunakan $.load
	// daftarKomentar.load(
	// 	'simpan_komentar.php',
	// 	{ nama: nama.val(), komentar: pesan.val() },
	// 	function() {
	// 		loading.hide();
	// 		nama.val('');
	// 		pesan.val('');
	// 	}
	// );

	// untuk menghentikan fungsi submit yang sebenarnya
	e.preventDefault();
});