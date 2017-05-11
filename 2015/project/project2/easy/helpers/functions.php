<?php

$config = array(
	"HOST"		=> "localhost",
	"USERNAME"	=> "root",
	"PASSWORD"	=> "root",
	"DB"		=> "043040023"
);

function koneksi($config) {
	$conn = mysqli_connect($config["HOST"], $config["USERNAME"], $config["PASSWORD"]) or die("Koneksi ke Database Gagal");

	mysqli_select_db($conn, $config["DB"]) or die("Database Salah!");

	return $conn;
}

function query($conn, $query) {
	$result = mysqli_query($conn, $query);

	$rows = array();
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;
}

function query_update($conn, $query) {
	$result = mysqli_query($conn, $query);

	$row = mysqli_fetch_assoc($result);

	return $row;
}

function delete($id, $conn) {

	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id='$id'");

	return mysqli_affected_rows($conn);

}

function tambah($hasil, $conn) {

	extract($hasil);
	$nama = htmlspecialchars($nama);
	$universitas = htmlspecialchars($universitas);
	$kota = htmlspecialchars($kota);

	$foto = 'nophoto.jpg';
	mysqli_query($conn, "
		INSERT INTO mahasiswa
			VALUES ('', '$nama', '$universitas', '$kota', '$fakultas', '$jurusan', '$foto')
	");

	return mysqli_affected_rows($conn);
}

function ubah($hasil, $id, $conn) {

	extract($hasil);
	$nama = htmlspecialchars($nama);
	$universitas = htmlspecialchars($universitas);
	$kota = htmlspecialchars($kota);
	
	mysqli_query($conn, "
		UPDATE mahasiswa
			SET 
				nama 	 	= '$nama',
				universitas = '$universitas',
				kota		= '$kota',
				fakultas 	= '$fakultas',
				jurusan	 	= '$jurusan'

		WHERE id = $id; 
	");

	return mysqli_affected_rows($conn);
}

function cek($id_yang_dicek, $id_sekarang) {
	echo ( $id_yang_dicek == $id_sekarang ) ? "selected" : "";
}

?>