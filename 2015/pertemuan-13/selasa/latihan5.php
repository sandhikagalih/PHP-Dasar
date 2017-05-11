<?php 
require "functions.php";
$conn = konek();

$provinsi = query($conn, "SELECT * FROM provinsi ORDER BY id ASC");
$kota = query($conn, "SELECT * FROM kota ORDER BY id ASC");

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
		// kota.load('cek_kota.php?id=' + provinsi.val());
		
		loader.show();
		kota.hide();
		$.get('cek_kota.php?id=' + provinsi.val(), function(hasil) {
			kota.html(hasil);
			loader.hide();
			kota.show();
		});
		
		// $.ajax({
		// 	url: 'cek_kota.php?id=' + provinsi.val(),
		// 	type: 'GET',
		// 	success: function(hasil) {
		// 		kota.html(hasil);
		// 	}
		// });	

	});

</script>
</body>
</html>
















