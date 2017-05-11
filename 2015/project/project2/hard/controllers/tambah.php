<?php
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

// koneksi ke database dan memilih database
$conn = koneksi($config);

if ( isset($_POST["submit"]) ) {
	if ( cek_gambar($_FILES['fupload']['name']) ) {
		if ( tambah($_POST, $conn, $_FILES['fupload']['name']) > 0 ) {
			echo "<script> 
					alert ('data berhasil ditambahkan!');
					document.location.href = '../';
				 </script>";
		} else {
			echo "<script> 
					alert ('data gagal ditambahkan!');
					document.location.href = '../';
				 </script>";
		}
	} else {
		echo "<script> 
				alert ('gambar tidak sesuai!');
				document.location.href = '../';
			 </script>";
	}

}

$fakultas = query($conn, "SELECT * FROM fakultas");
$jurusan  = query($conn, "SELECT * FROM jurusan WHERE id_fakultas = 1");

require '../views/tambah.view.php';

?>