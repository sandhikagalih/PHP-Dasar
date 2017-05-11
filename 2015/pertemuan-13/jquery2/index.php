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

<!doctype html>
<html>
<head>
	<title>Kolom Komentar</title>
	<style>
	body { 
		width: 500px; 
		margin: auto; 
		font-family: arial; 
	}
	ul { 
		list-style: none;
		padding: 0;
	}
	#daftar-komentar li:first-child {
		color: green;
	}
	.container { position: relative; }
	#loading { 
		position: absolute; 
		left: 160px;
		top: 3px;
	}
	.nama { 
		font-weight: bold; 
		font-size: 14px; 
	}
	.tanggal {
		font-size: 10px;
		color: #999;
	}
	.komentar {
		font-size: 11px;
		color: #333;
		font-style: italic;
	}
	</style>
	<script src="../__js__/jquery-2.0.3.js"></script>
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

	<div class="container">
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