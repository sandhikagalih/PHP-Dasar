<?php 
// cek apakah id terisi pada url atau tidak
if ( !isset($_GET["id"]) ) {
	header("Location: index.php");
}

// ambil data dari halaman index, lalu query ke database
// jangan lupa panggil halaman functions dulu
require 'helpers/functions.php';

// ambil id dari url
$id = $_GET["id"];
$query = "SELECT 
				m.id,
				m.nama,
				m.email,
				m.gambar,
				j.nama as jurusan,
				u.nama as universitas
			  FROM mahasiswa m, jurusan j, universitas u
			  WHERE
			  	m.jurusan = j.id_jurusan AND
			  	m.universitas = u.id_universitas AND
			  	id = $id";
$mhs = query($query)[0];

$judul_halaman = "Profil Mahasiswa";
require 'templates/header.php';
?>

<h2>Detail Mahasiswa</h2>

<div class="container">

		<div class="detail">
			<img src="img/<?php echo $mhs["gambar"]; ?>">
			<h2><?php echo $mhs["nama"]; ?></h2>
			<p><?php echo $mhs["email"]; ?></p>
			<p class="jurusan"><?php echo $mhs["jurusan"]; ?>, <?php echo $mhs["universitas"]; ?></p>
			<a href="index.php">Kembali</a>
		</div>

</div>

</body>
</html>