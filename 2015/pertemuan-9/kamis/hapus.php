<?php 
require 'functions.php';
$conn = konek();

$id = $_GET["id"];

if( hapus($conn, $id) ) {
	echo "<script>
			alert('Data Berhasil Dihapus');
			document.location.href = 'index.php';
		  </script>";
} else {
	echo "Data Gagal Dihapus";
}

 
?>