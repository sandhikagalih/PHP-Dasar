<?php 
require 'functions.php';
$conn = koneksi();

$id = $_GET["id"];

if( hapus($conn, $id) ) {
	echo "data berhasil dihapus <br>";
	echo "<a href='index.php'>Kembali</a>";
} else {
	echo "data gagal dihapus";
	echo "<a href='index.php'>Kembali</a>";
}