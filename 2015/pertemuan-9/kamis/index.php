<?php
require 'functions.php';
$conn = konek();

$hasil = query($conn, "SELECT * FROM karyawan");

?>

<!doctype html>
<html>
<head>
	<title>Daftar Karyawan</title>
</head>
<body>

<a href="tambah.php">Tambah Data Karyawan</a>

<h1>Daftar Karyawan</h1>

<?php foreach( $hasil as $baris ) : ?>
	
	<ul>
		<li><?= $baris["nama"]; ?></li>
		<li><a href="ubah.php?id=<?= $baris["id"]; ?>">Ubah</a> | <a href="hapus.php?id=<?= $baris["id"]; ?>" onclick="return confirm('yakin akan menghapus data?')">Hapus</a></li>
	</ul>

<?php endforeach; ?>

</body>
</html>







