<?php 
require 'functions.php';
$conn = koneksi();

if( !isset($_GET["id"]) ) {
	header("Location: index.php");
	exit;
}

$id = $_GET["id"];

$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = query($conn, $query);


if ( isset($_POST["ubah"]) ) {
	
	if ( ubah($conn, $_POST) ) {
		// $pesan = "<p>Data Berhasil Diubah!</p>";
		echo "<script>
				alert('Data Berhasil Diubah');
				document.location.href = 'index.php';	
			  </script>";
	} else {
		$pesan = "<p>Data Gagal Diubah!</p>";
	}

}

require 'ubah.view.php';
?>