<?php 
require "../helpers/functions.php";

$conn = koneksi($config);

$id = $_GET["id"];

if ( !isset($id) ) {
	header("Location: ../");
	exit;

} else {

	if ( delete($id, $conn) > 0 ) {
		echo "<script> 
				alert ('data berhasil dihapus!');
				document.location.href = '../';
			 </script>";
	} else {
		echo "<script> 
				alert ('data gagal dihapus!');
				document.location.href = '../';
			 </script>";
	}

}
 
?>