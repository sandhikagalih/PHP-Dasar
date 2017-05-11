<?php
function konek() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi gagal");
	mysqli_select_db($conn, "surveyPW2") or die("Database salah");

	return $conn;
}

function query($conn, $query) {
	$result = mysqli_query($conn, $query);

	if ( mysqli_num_rows($result) > 1 ) { // jika data yg diambil banyak

		$rows = array();
		while ( $row = mysqli_fetch_assoc($result) ) {
			$rows[] = $row;
		}

		return $rows;

	} else { // jika data yang diambilnya hanya 1 baris

		$row = array();
		$row = mysqli_fetch_assoc($result);

		return $row;
	}
}

function simpan($conn, $post) {
	$userid = $post["userid"];
	$questionid = $post["questionid"];
	$optionid = $post["optionid"];

	$query = "INSERT INTO answers
			  VALUES ('', '$userid', '$questionid', '$optionid', '') 	
			 ";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

?>