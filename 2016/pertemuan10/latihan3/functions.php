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



function tambah($data) {
	$conn = koneksi();

	// menangkap data dari form
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$universitas = htmlspecialchars($data["universitas"]);
	$gambar = htmlspecialchars($data["gambar"]);

	// insert data ke database
	$query = "INSERT INTO mahasiswa
				VALUES
			('', '$nama', '$email', '$jurusan', '$universitas', '$gambar')";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function hapus($id) {
	$conn = koneksi();
	
	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

	return mysqli_affected_rows($conn);
}






?>