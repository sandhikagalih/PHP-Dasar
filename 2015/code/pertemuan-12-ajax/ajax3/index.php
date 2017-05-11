<?php 
require 'functions.php';
$conn = konek();

$komentar = query($conn, "SELECT * FROM komentar ORDER BY id DESC");

if( isset($_POST["submit"]) ) {
	if( simpan($conn, $_POST) > 0) {
		echo "<script>
		 		alert('Data Berhasil Disimpan');
		 	  </script>";
	} else {
		echo "<script>
		 		alert('Data Gagal Disimpan');
		 	  </script>";
	}
}
?>

<!-- 

ajax3
memperbaiki aplikasi komentar pada ajax2
menambahkan gambar loading yang ditampilkan pada saat menunggu data selesai diproses
membuat file simpan_komentar.php seolah2 lambat mengirimkan data dengan menggunakan fungsi sleep()
dilakukan agar gambar loading muncul
juga menambahkan css/style.css

Sandhika Galih
Pemrograman Web 2 - IT304
Teknik Informatika
Universitas Pasundan
	
-->

<!doctype html>
<html>
<head>
	<title>Kolom Komentar</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<form action="" method="post" id="form-komentar">
		<ul>
			<li>
				<label for="nama">Nama: </label>
				<input type="text" name="nama" id="nama" autofocus required>
			</li>
			<li>
				<label for="komentar">Komentar : </label><br>
				<textarea name="komentar" id="komentar" cols="30" rows="10" required></textarea>
			</li>
			<li>
				<button type="submit" name="submit" id="submit">Simpan Komentar</button>
			</li>
		</ul>
	</form>

	<div>
		<div id="loading" style="display: none;"><img src="images/loader.gif"></div>
		<h3>Daftar Komentar</h3>
		<ul id="daftar-komentar">
			<?php foreach( $komentar as $baris ) : ?>
			<li>
				<span class="tanggal">(<?= $baris["waktu"]; ?>)</span>
				<span class="nama"><?= $baris["nama"]; ?> : </span>
				<span class="komentar"><?= $baris["pesan"]; ?> </span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>


<script src="js/script.js"></script>
</body>
</html>