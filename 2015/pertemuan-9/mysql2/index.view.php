<!doctype html>
<html>
<head>
	<title>Daftar Teman</title>
</head>
<body>

<div><a href="tambah.php">Tambah Data Mahasiswa</a></div>

<h1>Daftar mahasiswa</h1>

<?php 
	$result = query($conn, "SELECT * FROM mahasiswa");
	
	foreach ( $result as $row ) : ?>
	
	<ul>
		<li><strong><?= $row["nama"]; ?></strong></li>
		<li><?= $row["universitas"]; ?></li>
		<li><a href="ubah.php?id=<?= $row["id"]; ?>">Ubah Data</a> | <a href="hapus.php?id=<?= $row["id"]; ?>">Hapus Data</a></li>
	</ul>
	
	<?php endforeach; ?>

</body>
</html>