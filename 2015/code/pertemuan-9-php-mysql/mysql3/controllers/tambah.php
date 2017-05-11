<?php 
require '../helpers/functions.php';
$conn = koneksi();


if ( isset($_POST["tambah"]) ) {
	
	if ( tambah($conn, $_POST) ) {
		$pesan = "Data Berhasil Ditambahkan!";
	} else {
		$pesan = "Data Gagal Ditambahkan!";
	}

}

require '../views/tambah.view.php';
?>