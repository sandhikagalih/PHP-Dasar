<!doctype html>
<html>
<head>
	<title>Daftar Teman</title>
</head>
<body>

<div><a href="controllers/tambah.php">Tambah Data Mahasiswa</a></div>

<h1>Daftar mahasiswa</h1>

<?php 
	$result = query($conn, "SELECT * FROM mahasiswa");
	
	foreach ( $result as $row ) : ?>
	
	<ul>
		<li><strong><?= $row["nama"]; ?></strong></li>
		<li><?= $row["universitas"]; ?></li>
		<li><a href="controllers/ubah.php?id=<?= $row["id"]; ?>">Ubah Data</a> | <a href="controllers/hapus.php?id=<?= $row["id"]; ?>">Hapus Data</a></li>
	</ul>
	
	<?php endforeach; ?>

</body>
</html>