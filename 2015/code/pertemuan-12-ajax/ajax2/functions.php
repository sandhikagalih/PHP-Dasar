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


function simpan($conn, $post) {
	$waktu = date('d M Y H:i:s');
	$nama = htmlspecialchars($post["nama"]);
	$komentar = htmlspecialchars($post["komentar"]);

	$query = "INSERT INTO komentar
			  VALUES ('', '$nama', '$komentar', '$waktu') 	
			 ";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

?>