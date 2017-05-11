<?php

function konek() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Tidak Bisa konek ke Database");

	mysqli_select_db($conn, "penjualan_handphone") or die("Database Salah");

	return $conn;
}


function query($conn, $string) {
	$result = mysqli_query($conn, $string);

	$rows = array();
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;
}


function tambah($conn, $post = array()) {
	// var_dump($post["nama"]); die;
	// $nama = $post["nama"];
	$query = "INSERT INTO karyawan
				VALUES ('', '{$post["nama"]}')
			 ";

	mysqli_query($conn, $query);

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