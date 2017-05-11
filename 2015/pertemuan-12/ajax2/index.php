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

ajax2
menggunakan ajax untuk aplikasi kelola komentar sederhana
data komentar yang disubmit otomatis muncul di bagian bawah halaman

Sandhika Galih
Pemrograman Web 2 - IT304
Teknik Informatika
Universitas Pasundan
	
-->

<!doctype html>
<html>
<head>
	<title>Kolom Komentar</title>
	<style>
	body { width: 500px; margin: auto;}
	ul { 
		list-style: none;
		padding: 0;
	}
	</style>
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