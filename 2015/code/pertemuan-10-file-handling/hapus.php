<?php 
if( !isset($_GET["id"]) ) {
	header("location: index.php");
}

require 'functions.php';
$conn = konek();

$id = $_GET["id"];

if( hapus($conn, $id) ) {
	echo "<script>
			alert('Data Berhasil Dihapus');
			document.location.href = 'gallery.php';	
		  </script>";
} else {
	echo "<script>
			alert('Data Gagal Dihapus');
			document.location.href = 'gallery.php';	
		  </script>";
}

?>