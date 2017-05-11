<?php 
require 'functions.php';
$conn = konek();

$komentar = query($conn, "SELECT * FROM komentar ORDER BY waktu DESC");

if( isset($_POST["submit"]) ) {

	if( simpan($conn, $_POST) > 0 ) {
		echo "
			<script>
				alert('Komentar berhasil ditambahkan');
			</script>
		";
	} else {
		echo "
			<script>
				alert('Komentar gagal ditambahkan');
			</script>
		";
	}

}

?>
<!doctype html>
<html>
<head>
	<title>Aplikasi Komentar</title>
	<script src="jquery-2.0.3.js"></script>
</head>
<body>

<form action="" method="post" id="formKomentar">
	<label for="nama">Nama:</label>
	<input type="text" name="nama" id="nama">
	<br>
	<label for="komentar">Komentar:</label>
	<br>
	<textarea name="komentar" id="komentar"></textarea>
	<br>
	<button type="submit" name="submit">Kirim Komentar</button>
</form>

<h3>Daftar Komentar</h3>
<div class="daftarKomentar">
	
	<ul>
		<?php foreach( $komentar as $baris ) : ?>
			<li>(<?= $baris["waktu"]; ?>) <?= $baris["nama"]; ?> : <?= $baris["pesan"]; ?></li>
		<?php endforeach; ?>
	</ul>

</div>


<script>
	var form = $('#formKomentar'),
		container = $('.daftarKomentar'),
		nama = $('#nama'),
		komentar = $('#komentar');

	form.submit(function(e) {
		e.preventDefault();
		container.load('input_komentar.php', {'nama': nama.val(), 'komentar': komentar.val()}, function() {
			nama.val('');
			komentar.val('');
		});
	});

</script>
</body>
</html>




