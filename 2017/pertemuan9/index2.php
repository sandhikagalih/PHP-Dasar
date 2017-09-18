<?php
// koneksi ke mysql, pilih database
$conn = mysqli_connect("localhost", "root", "root", "pw_043040023");

// query ke DB / menyiapkan data 
$result = mysqli_query($conn, "select * from mahasiswa");

// mencacah data hasil query
// 4 cara :
// mysqli_fetch_row : array numerik
// mysqli_fetch_assoc : array associative
// mysqli_fetch_array : keduanya
// mysqli_fetch_object : object

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
	<?php while( $row = mysqli_fetch_assoc($result) ) { ?>
	<tr>
		<td><?= $i; ?></td>
		<td><a href="">ubah</a> | <a href="">hapus</a></td>
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