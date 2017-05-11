<?php 
require 'functions.php';
$conn = konek();

$provinsi = query($conn, "SELECT * FROM provinsi");
$kota = query($conn, "SELECT * FROM kota ORDER BY nama ASC");

?>

<!-- 

ajax1
menggunakan ajax untuk menampilkan kota pada combobox, berdasarkan provinsi yang dipilih

Sandhika Galih
Pemrograman Web 2 - IT304
Teknik Informatika
Universitas Pasundan
	
-->

<!doctype html>
<html>
<head>
	<title>AJAX</title>
</head>
<body>

<form action="" method="post">
	<select name="provinsi" id="provinsi">
		<option disabled selected>Pilih Provinsi</option>
		<?php foreach( $provinsi as $baris ) : ?>
				<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
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
	var provinsi = document.getElementById('provinsi'),
		kota = document.getElementById('kota');

	provinsi.onchange =  function() {

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

		// set url beserta data yang akan dikirimkan dengan method get
		// data diambil berdasarkan value dari pilihan provinsi di combobox
		var url = "cek_kota.php?id=" + provinsi.value;

		xhr.onreadystatechange = function() {
			if( (xhr.readyState == 4) && (xhr.status == 200) ) {
				kota.innerHTML = xhr.responseText;
			}
		}
		xhr.open("GET", url, true);
		xhr.send();

	}
</script>
</body>
</html>