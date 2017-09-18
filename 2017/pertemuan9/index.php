<?php
require 'functions.php';
$mahasiswa = query("select * from mahasiswa");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Administrator</title>
</head>
<body>

<h1>Halaman Administrator</h1>

<a href="">Tambah data Mahasiswa</a>
<br><br>

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
	<?php $i = 1; ?>
	<?php foreach( $mahasiswa as $row ) { ?>
	<tr>
		<td><?= $i; ?></td>
		<td><a href="">ubah</a> | <a href="hapus.php?id=<?php echo $row["id"]; ?>">hapus</a></td>
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


</body>
</html>