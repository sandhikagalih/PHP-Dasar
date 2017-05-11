<?php 
require 'functions.php';
$conn = konek();

$provinsi = query($conn, "SELECT * FROM provinsi");
$kota = query($conn, "SELECT * FROM kota ORDER BY nama ASC");

?>

<!doctype html>
<html>
<head>
	<title>AJAX</title>
	<script src="../__js__/jquery-2.0.3.js"></script>
</head>
<body>

<form action="" method="get">
	<select name="provinsi" id="provinsi">
		<option disabled selected>Pilih Provinsi</option>
		<?php foreach( $provinsi as $baris ) : ?>
			<?php if( isset($_POST["provinsi"]) && $_POST["provinsi"] == $baris["id"] ) : ?>
				<option value="<?= $baris["id"]; ?>" selected><?= $baris["nama"]; ?></option>
			<?php else : ?>
				<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>
	<select name="kota" id="kota">
		<option disabled selected>Pilih Kota</option>
		<?php foreach( $kota as $baris ) : ?>
		<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
		<?php endforeach; ?>
	</select>
</form>


<script>
	prov = $('#provinsi');
	prov.change(function() {

		// dengan $.ajax
		$.ajax({
			url: 'cek_kota.php?id=' + prov.val(),
			type: 'GET',
			// data: { id: prov.val() },
			success: function(hasil) {
				$('#kota').html(hasil);
			}
		});

		// dengan $.get
		// $.get('cek_kota.php?id=' + prov.val(), function(hasil) {
		// 	$('#kota').html(hasil);
		// });

		// dengan $.load
		// $('#kota').load('cek_kota.php?id=' + prov.val());
	});
</script>
</body>
</html>