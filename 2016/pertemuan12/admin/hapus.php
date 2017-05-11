<?php 
session_start();
if (!isset($_GET["id"]) || !isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

if( hapus($_GET["id"]) > 0 ) {
	echo "<script>
			alert('data berhasil dihapus!');
			document.location.href = 'index.php';
		 </script>";
} else {
	echo "<script>
			alert('data gagal dihapus!');
			document.location.href = 'index.php';
		 </script>";
}

?>