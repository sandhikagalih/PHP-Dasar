<?php 

function koneksi() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi ke Database Gagal");

	mysqli_select_db($conn, "coba_mysql") or die("Database Salah!");

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


function tambah($conn, $post = array()) {

	// $query = "INSERT INTO mahasiswa
	// 			VALUES ('', '{$post["nama"]}', '{$post["universitas"]}')
	// 		 ";
	
	$query = sprintf("INSERT INTO mahasiswa VALUES ('', '%s', '%s')",
						$post["nama"], $post["universitas"] );

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}


function hapus($conn, $id) {
	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id");

	return mysqli_affected_rows($conn);

}


function ubah($conn, $post = array()) {

	$query = "UPDATE mahasiswa
				SET nama = '{$post["nama"]}', universitas = '{$post["universitas"]}'
				WHERE id = {$post["id"]}
			 ";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);

}

?>