<?php 
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
		],
		[
			"gambar" => "anggoro.jpg",
			"nama" => "Anggoro Ari",
			"nrp" => "033040002",
			"email" => "anggoro@unpas.ac.id"
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

	<ul>
		<?php foreach( $mahasiswa as $mhs ) : ?>
		<li><a href="detail.php?gambar=<?php echo $mhs["gambar"]; ?>&nama=<?php echo $mhs["nama"]; ?>&nrp=<?php echo $mhs["nrp"]; ?>&email=<?php echo $mhs["email"]; ?>"><?php echo $mhs["nama"]; ?></a></li>
		<?php endforeach; ?>
	</ul>
	
</body>
</html>