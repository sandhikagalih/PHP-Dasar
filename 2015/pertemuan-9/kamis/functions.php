<?php 
function konek() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi Gagal");
	mysqli_select_db($conn, "distro") or die("Database Salah!");

	return $conn;
}


function query($conn, $string) {

	$query = mysqli_query($conn, $string);
	$hasil = array();
	while( $isi = mysqli_fetch_assoc($query) ) {
		$hasil[] = $isi;
	}

	return $hasil;
}


function tambah($conn, $post = array()) {
	$nama = $post["nama"];
	mysqli_query($conn, "INSERT INTO karyawan VALUES ('', '$nama')");

	return mysqli_affected_rows($conn);
}


function hapus($conn, $id) {

	mysqli_query($conn, "DELETE FROM karyawan WHERE id = $id");

	return mysqli_affected_rows($conn);
}


function ubah($conn, $post) {

	$query = "UPDATE karyawan
				SET nama = '{$post["nama"]}'
			  WHERE id = {$post["id"]}
			 ";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}










?>