<?php
$conn = mysqli_connect("localhost", "root", "root", "pw_043040023");

	
function query($sql) {
	global $conn;
	$result = mysqli_query($conn, $sql);

	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;
}

function hapus($id) {
	global $conn;
	mysqli_query($conn, "delete from mahasiswa where id = $id");

	return mysqli_affected_rows($conn);
}


function tambah($data) {
	global $conn;

	$nrp = htmlspecialchars($data["nrp"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);

	// jika user tidak pilih gambar
	if( $_FILES['gambar']['error'] == 4 ) {
		echo "<script>
				alert('harap pilih gambar terlebih dahulu!');
				document.location.href = 'tambah.php';
			  </script>";
		return false;
	}

	if( ! cek_gambar() ) {
		return false;
	}

	// buat nama file baru
	$ekstensiGambar = explode('.', $_FILES['gambar']['name']);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	$nama_file_baru = uniqid() . '.' . $ekstensiGambar;
	$gambar = $nama_file_baru;

	move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $gambar);

	$sql = "INSERT INTO mahasiswa
				VALUES
			('', '$nrp', '$nama', '$email', '$jurusan', '$gambar')";

	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn);
}


function cek_gambar() {
	// ambil data gambar
	$gambar = $_FILES["gambar"]["name"];
	$tmp_name = $_FILES["gambar"]["tmp_name"];
	$ukuran = $_FILES["gambar"]["size"];
	$tipe = $_FILES["gambar"]["type"];
	$error = $_FILES["gambar"]["error"];

	// pengecekan gambar
	// jika ukuran file melebihi 5MB
	if( $ukuran > 5000000 ) {
		echo "<script>
				alert('ukuran file terlalu besar!');
				document.location.href = '';
			  </script>";
		return false;
	}

	// jika bukan gambar
	$tipeGambarAman = ['jpg', 'jpeg', 'png', 'gif'];
	$ekstensiGambar = explode('.', $gambar);
	$ekstensiGambar = strtolower(end($ekstensiGambar));

	if( ! in_array($ekstensiGambar, $tipeGambarAman) ) {
		echo "<script>
				alert('yang anda pilih bukan gambar!');
				document.location.href = '';
			  </script>";
		return false;
	}

	return true;
}


function ubah($data) {
	global $conn;

	$id = $data["id"];
	$nrp = htmlspecialchars($data["nrp"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$gambar = htmlspecialchars($data["gambar_lama"]);

	// cek apakah user upload gambar baru
	if( $_FILES['gambar']['error'] === 0 ) {
		// cek gambar
		if( ! cek_gambar() ) {
			return false;
		}

		// upload gambar baru
		$ekstensiGambar = explode('.', $_FILES['gambar']['name']);
		$ekstensiGambar = strtolower(end($ekstensiGambar));
		$nama_file_baru = uniqid() . '.' . $ekstensiGambar;
		$gambar = $nama_file_baru;

		move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $gambar);
	}

	$sql = "UPDATE mahasiswa SET
				nrp = '$nrp',
				nama = '$nama',
				email = '$email',
				jurusan = '$jurusan',
				gambar = '$gambar'
			WHERE
				id = $id
			";

	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn);
}


function register($data) {
	global $conn;

	$username = htmlspecialchars($_POST["username"]);
	$password = htmlspecialchars($_POST["password"]);
	$email = htmlspecialchars($_POST["email"]);

	// cek username sudah pernah ada atau belum
	$cek_username = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

	if( mysqli_num_rows($cek_username) === 1 ) {
		echo "<script>
				alert('username sudah terpakai!');
				document.location.href = '';
			  </script>";
		return false;
	}

	// tambahkan user baru ke database
	// enkripsi password
	$password = password_hash($password, PASSWORD_DEFAULT);

	// insert ke DB
	$sql = "INSERT INTO user VALUES ('', '$username', '$password', '$email', CURRENT_TIMESTAMP)";
	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn);
}


















?>