<?php

function koneksi() {
	$conn = mysqli_connect("localhost", "root", "root", "pw_043040023");
	return $conn;
}


function query($sql) {
	$conn = koneksi();
	$result = mysqli_query($conn, $sql);

	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;
}


function hapus($id) {
	$conn = koneksi();
	mysqli_query($conn, "delete from mahasiswa where id = $id");

	return mysqli_affected_rows($conn);
}









?>