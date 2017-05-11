<?php /**
 * functions.php
 * file berisi fungsi-fungsi yang dibutuhkan untuk aplikasi
 */



function koneksi() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi ke DB gagal!");
	mysqli_select_db($conn, "pw2_043040023") or die("Database salah!");

	return $conn;
}



function query($string) {
	$conn = koneksi();

	$results = mysqli_query($conn, $string);
	$rows = array();
	while( $row = mysqli_fetch_assoc($results) ) {
		$rows[] = $row;
	}

	return $rows;
}



function tambah($data) {
	$conn = koneksi();

	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$universitas = htmlspecialchars($data["universitas"]);
	$gambar = htmlspecialchars($data["gambar"]);

	$query = "INSERT INTO mahasiswa
				VALUES ('', '$nama', '$email', '$jurusan', 
			  '$universitas', '$gambar')";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}



function hapus($id) {
	$conn = koneksi();
	
	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

	return mysqli_affected_rows($conn);
}



function ubah($data) {
	$conn = koneksi();

	$id = $data["id"];
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$universitas = htmlspecialchars($data["universitas"]);
	$gambar = htmlspecialchars($data["gambar"]);

	$query = "UPDATE mahasiswa
				SET
				nama = '$nama',
				email = '$email',
				jurusan = '$jurusan',
				universitas = '$universitas',
				gambar = '$gambar'
			  WHERE id = $id";

	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}



?>