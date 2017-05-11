<?php
function konek() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi gagal");
	mysqli_select_db($conn, "pertemuan12") or die("Database salah");

	return $conn;
}

function query($conn, $query) {
	$results = mysqli_query($conn, $query);

	$rows = array();
	while ( $row = mysqli_fetch_assoc($results) ) {
		$rows[] = $row;
	}

	return $rows;
}

?>