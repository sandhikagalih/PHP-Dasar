<?php
require 'helper/functions.php';
$conn = konek();

if( isset($_POST["pilih"]) ) {
	$daftar_film = query($conn, "SELECT film_id, title FROM film WHERE title LIKE '{$_POST["film"]}%'");
}

?>

<!doctype html>
<html>
<head>
	<title>Latihan Praktikum Pertemuan 12</title>
</head>
<body>

	<form action="" method="post" id="cari-film">
		<h2>Cari Film Berdasarkan Nama</h2>
		<select name="film" id="film">
			<?php 
			 $alphabet = str_split('abcdefghijklmnopqrstuvwxyz');
			 foreach( $alphabet as $huruf ) : ?>
			 	<option value="<?= $huruf ?>"><?= $huruf ?></option>
			<?php endforeach; ?>
		</select>
		<button type="submit" name="pilih" id="pilih">Pilih</button>
	</form>

	<div id="container">
		<?php if( isset($daftar_film) ) : ?>
		<ol>
			<?php foreach( $daftar_film as $film ) : ?>
				<li><a href="detail_film.php?id=<?= $film["film_id"]; ?>"><?= $film["title"]; ?></a></li>
			<?php endforeach; ?>
		</ol>
		<?php endif; ?>
	</div>

<script src="js/mini.js"></script>
</body>
</html>