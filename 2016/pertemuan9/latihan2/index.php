<?php 
require 'functions.php';

$mahasiswa = query("SELECT * FROM mahasiswa");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tampil Data Mahasiswa</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Data Mahasiswa</h2>
<?php foreach( $mahasiswa as $mhs ) { ?>
	<ul>
		<li><img src="../img/<?php echo $mhs["gambar"]; ?>"></li>
		<li><?php echo $mhs["nama"]; ?></li>
		<li><?php echo $mhs["email"]; ?></li>
		<li><?php echo $mhs["jurusan"]; ?></li>
		<li><?php echo $mhs["universitas"]; ?></li>
	</ul>
<?php } ?>
	
</body>
</html>