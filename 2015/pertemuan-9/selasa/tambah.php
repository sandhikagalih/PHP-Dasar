<?php 
require 'functions.php';
$conn = konek();

if( isset($_POST["tambah"]) ) {

	if( tambah($conn, $_POST) ) {
		echo "Data Berhasil Ditambahkan!";
	} else {
		echo "Data Gagal Ditambahkan!";
	}

}
 
?>

<!doctype html>
<html>
<head>
	<title>Tambah Data Karyawan</title>
</head>
<body>

<h1>Tambah Data Karyawan</h1>
<form action="" method="post">
	
	<label for="nama">Nama</label>
	<input type="text" name="nama" id="nama" required>
	<br>
	<input type="submit" name="tambah" value="Tambah">

</form>

</body>
</html>