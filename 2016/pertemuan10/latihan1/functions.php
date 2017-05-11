<?php 

// koneksi ke database
function koneksi() {
	$conn = mysqli_connect("localhost", "root", "root") or die("koneksi gagal");
	mysqli_select_db($conn, "pw2_043040023");

	return $conn;
}


function query($query) {
	$conn = koneksi();

	$result = mysqli_query($conn, $query);
	$rows = array();
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;

}














?>