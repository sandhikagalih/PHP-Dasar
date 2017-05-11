<?php 
require 'functions.php';
$mahasiswa = query("SELECT * FROM mahasiswa ORDER BY id DESC");
$i = 1;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Admin</title>
</head>
<body>

<h2>Daftar Mahasiswa</h2>

<a href="tambah.php">Tambah data mahasiswa</a>

<br><br>

<table border="1" cellspacing="0" cellpadding="5">

	<tr>
		<th>#</th>
		<th>Aksi</th>
		<th>Gambar</th>
		<th>Nama</th>
		<th>Email</th>
		<th>Jurusan</th>
		<th>Universitas</th>
	</tr>
	<?php foreach( $mahasiswa as $row ) : ?>
	<tr>
		<td><?= $i; ?></td>
		<td>
			<a href="">ubah</a> | 
			<a href="hapus.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('yakin?');">hapus</a>
		</td>
		<td align="center"><img src="img/<?= $row["gambar"]; ?>" width="40%"></td>
		<td><?= $row["nama"]; ?></td>
		<td><?= $row["email"]; ?></td>
		<td><?= $row["jurusan"]; ?></td>
		<td><?= $row["universitas"]; ?></td>
	</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
</table>
	
</body>
</html>