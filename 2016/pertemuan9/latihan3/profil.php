<?php 
require 'functions.php';

$id = $_GET["id"];
$mahasiswa = query("SELECT * FROM mahasiswa WHERE id = $id")[0];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Detail Data Mahasiswa</title>
</head>
<body>
<h2>Detail Data Mahasiswa</h2>

<ul>
	<li><img src="../img/<?php echo $mahasiswa["gambar"]; ?>"></li>
	<li><?php echo $mahasiswa["nama"]; ?></li>
	<li><?php echo $mahasiswa["email"]; ?></li>
	<li><?php echo $mahasiswa["jurusan"]; ?></li>
	<li><?php echo $mahasiswa["universitas"]; ?></li>
	<li><a href="index.php">Kembali</a></li>
</ul>
	
</body>
</html>