<?php 

function koneksi() {
	$conn = mysqli_connect("localhost", "root", "root") 
				or die("Koneksi ke Database Gagal");

	mysqli_select_db($conn, "coba_mysql")
		or die("Database Salah!");

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

