<?php
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

// koneksi ke database dan memilih database
$conn = koneksi($config);

$id = $_REQUEST["id"];

if ( !isset($id) ) {
	header("Location: ../"); exit;
} else {
	if ( isset($_POST["submit"]) ) {
		if ( ubah($_POST, $id, $conn) > 0 ) {
			echo "<script> 
					alert ('data berhasil diubah!');
					document.location.href = '../';
				 </script>";
		} else {
			echo "<script> 
					alert ('data gagal diubah!');
					document.location.href = '../';
				 </script>";
		}

	} else {
		$hasil = query_update($conn, "SELECT * FROM mahasiswa WHERE id = $id");
	}

	$fakultas = query($conn, "SELECT * FROM fakultas");
	$jurusan  = query($conn, "SELECT * FROM jurusan");

	require '../views/ubah.view.php';
}

?>