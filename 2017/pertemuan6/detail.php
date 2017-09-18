<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Detail Profil Mahasiswa</title>
</head>
<body>
	<h3>Profil Mahasiswa</h3>
	<ul>
		<li><img src="img/<?php echo $_GET["gambar"]; ?>"></li>
		<li><?php echo $_GET["nama"]; ?></li>
		<li><?php echo $_GET["nrp"]; ?></li>
		<li><?php echo $_GET["email"]; ?></li>
	</ul>

	<a href="array3.php">Kembali ke Daftar Mahasiswa</a>
</body>
</html>