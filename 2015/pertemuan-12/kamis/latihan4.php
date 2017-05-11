<?php 
require 'functions.php';
$conn = konek();

$provinsi = query($conn, "SELECT * FROM provinsi");
$kota = query($conn, "SELECT * FROM kota");
  
?>

<!doctype html>
<html>
<head>
	<title>Latihan 4</title>
</head>
<body>

<form action="">
	
	<select name="provinsi" id="provinsi">
		<option value="">Pilih Provinsi</option>
		<?php foreach( $provinsi as $baris ) : ?>
			<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
		<?php endforeach; ?>
	</select>

	<select name="kota" id="kota">
		<option value="">Pilih Kota</option>
		<?php foreach( $kota as $baris ) : ?>
			<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
		<?php endforeach; ?>
	</select>

</form>

<script>
	
	var provinsi = document.getElementById('provinsi'),
		kota = document.getElementById('kota');

	provinsi.onchange = function() {

		// ajax
		var xhr = null;

		if( window.XMLHttpRequest ) {
			xhr = new XMLHttpRequest();
		} else {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if( xhr == null ) {
			alert("browser tidak mendukung ajax!");
			return;
		}

		xhr.onreadystatechange = function() {
			if( (xhr.readyState == 4) && (xhr.status == 200) ) {
				kota.innerHTML = xhr.responseText;
			}
		}

		xhr.open("GET", "cek_kota.php?id=" + provinsi.value, true);
		// xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.send();

	}

</script>
</body>
</html>



















