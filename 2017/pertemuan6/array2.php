<?php 
// ARRAY ASSOCIATIVE
// Array yang indexnya string, yang kita buat sendiri
$mahasiswa = [
		[
			"gambar" => "sandhika.jpeg",
			"nama" => "Sandhika Galih",
			"nrp" => "043040023",
			"email" => "sandhikagalih@gmail.com"
		],
		[
			"gambar" => "doddy.jpg",
			"nama" => "Doddy Ferdiansyah",
			"nrp" => "033040010",
			"email" => "doddy@yahoo.com"
		],
		[
			"gambar" => "fajar.jpg",
			"nama" => "Fajar Darmawan",
			"nrp" => "023040100",
			"email" => "fajar_if@gmail.com"
		]
	];

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Daftar Mahasiswa</title>
</head>
<body>
	<h3>Daftar Mahasiswa</h3>
	<?php foreach( $mahasiswa as $mhs ) : ?>
	<ul>
		<li><img src="img/<?php echo $mhs["gambar"]; ?>"></li>
		<li><?php echo $mhs["nama"]; ?></li>
		<li><?php echo $mhs["nrp"]; ?></li>
		<li><?php echo $mhs["email"]; ?></li>
	</ul>
	<?php endforeach; ?>
</body>
</html>









