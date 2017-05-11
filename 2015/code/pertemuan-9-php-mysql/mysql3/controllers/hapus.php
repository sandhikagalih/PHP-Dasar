<?php 
require '../helpers/functions.php';
$conn = koneksi();

$id = $_GET["id"];

if( hapus($conn, $id) ) {
	echo "data berhasil dihapus <br>";
	echo "<a href='../'>Kembali</a>";
} else {
	echo "data gagal dihapus";
	echo "<a href='../'>Kembali</a>";
}