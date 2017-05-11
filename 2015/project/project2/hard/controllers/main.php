<?php
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

// koneksi ke database dan memilih database
$conn = koneksi($config);

$query = "
	SELECT
		mahasiswa.id,
		mahasiswa.foto,
		mahasiswa.nama,
		mahasiswa.universitas,
		mahasiswa.kota,
		fakultas.nama as fakultas,
		jurusan.nama as jurusan
	FROM mahasiswa, fakultas, jurusan
	WHERE
		mahasiswa.fakultas = fakultas.id AND
		mahasiswa.jurusan  = jurusan.id
	ORDER BY mahasiswa.id;
";

$hasil = query($conn, $query);
$i = 1;


require '../views/main.view.php';

?>