<?php
require 'functions.php';

if( isset($_GET['cari']) ) {
	$keyword = $_GET['keyword'];
	$sql = "SELECT * FROM mahasiswa
				WHERE
			 nrp LIKE '%$keyword%' OR
			 nama LIKE '%$keyword%' OR
			 email LIKE '%$keyword%' OR
			 jurusan LIKE '%$keyword%'
			";
	$mahasiswa = query($sql);
} else {
	$mahasiswa = query("select * from mahasiswa");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Administrator</title>
</head>
<body>

<h1>Halaman Administrator</h1>

<a href="tambah.php">Tambah data Mahasiswa</a>
<br><br>

<form action="" method="get">
	<input type="search" name="keyword" placeholder="masukkan keyword pencarian.." size="40" autocomplete="off" id="keyword">
	<button type="submit" name="cari" id="cari">Cari!</button>
</form>

<br>

<div id="container">
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<th>#</th>
		<th>Aksi</th>
		<th>Gambar</th>
		<th>NRP</th>
		<th>Nama</th>
		<th>Email</th>
		<th>Jurusan</th>
	</tr>
	
	<?php if( empty($mahasiswa) ) : ?>
		<tr>
			<td colspan="7" align="center">data mahasiswa tidak ditemukan</td>
		</tr>
	<?php endif; ?>

	<?php $i = 1; ?>
	<?php foreach( $mahasiswa as $row ) { ?>
	<tr>
		<td><?= $i; ?></td>
		<td><a href="ubah.php?id=<?php echo $row["id"]; ?>">ubah</a> | <a href="hapus.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('yakin?')">hapus</a></td>
		<td>
			<img src="img/<?= $row["gambar"]; ?>" width="50">
		</td>
		<td><?= $row["nrp"]; ?></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["email"]; ?></td>
		<td><?= $row["jurusan"]; ?></td>
	</tr>
	<?php $i++; ?>
	<?php } ?>
</table>
</div>

<script src="script.js"></script>
</body>
</html>