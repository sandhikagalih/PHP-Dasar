<?php 
require "functions.php";
$conn = konek();

$provinsi = query($conn, "SELECT * FROM provinsi ORDER BY nama ASC");
$kota = query($conn, "SELECT * FROM kota ORDER BY nama ASC");

?>

<!doctype html>
<html>
<head>
	<title>Latihan 5</title>
	<script src="jquery-2.0.3.js"></script>
	<style>
		.loader { display: none; }
	</style>
</head>
<body>

<form action="">
	
	<select name="provinsi" id="provinsi">
		<option value="">Pilih Provinsi</option>
		<?php foreach( $provinsi as $baris ) : ?>
			<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
		<?php endforeach; ?>
	</select>
	
	<img src="resource/loader.gif" class="loader">

	<select name="kota" id="kota">
		<?php foreach( $kota as $baris ) : ?>
			<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
		<?php endforeach; ?>
	</select>

</form>

<script>
	var provinsi = $('#provinsi'),
		kota = $('#kota'),
		loader = $('.loader');

	provinsi.change(function() {
		loader.show();
		kota.hide();
		kota.load('cek_kota_b.php?id=' + provinsi.val(), function() {
			loader.hide();
			kota.show();
		});
	});

</script>
</body>
</html>
















