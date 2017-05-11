<?php 
require 'functions.php';

if( isset($_POST["tambah"]) ) {
	// cek keberhasilan query
	if( tambah($_POST) > 0 ) {
		echo "<script>
				alert('data berhasil diinputkan!');
				document.location.href = 'index.php';
			  </script>";
	} else {
		echo "<script>
				alert('data gagal diinputkan!');
				document.location.href = 'index.php';
			  </script>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Form Tambah Data Mahasiswa</title>
</head>
<body>
<h2>Form Tambah Data Mahasiswa</h2>

<form action="" method="post">

	<label for="nama">Nama:</label> <br>
	<input type="text" name="nama" id="nama" required> <br><br>

	<label for="email">Email:</label> <br>
	<input type="text" name="email" id="email" required> <br><br>

	<label for="jurusan">Jurusan:</label> <br>
	<input type="text" name="jurusan" id="jurusan" required> <br><br>

	<label for="universitas">Universitas:</label> <br>
	<input type="text" name="universitas" id="universitas" required> <br><br>

	<label for="gambar">Gambar:</label> <br>	
	<input type="text" name="gambar" id="gambar" required> <br><br>

	<button type="submit" name="tambah">Tambah Data</button>

</form>
	
</body>
</html>