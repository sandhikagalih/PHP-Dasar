<?php 
require 'functions.php';

if( isset($_GET["id"]) ) {
	// cek keberhasilan query
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
}
?>