<?php 
// $mahasiswa = [
// ["Sandhika Galih", "043040023", "sandhikagalih@unpas.ac.id", "Teknik Informatika"],
// ["033040001", "Doddy Ferdiansyah", "doddy@gmail.com", "Teknik Industri"]
// ];

// Array Associative
// definisinya sama seperti array numerik, kecuali
// key-nya adalah string yang kita buat sendiri
$mahasiswa = [
	[	
		"nrp" => "043040023",
		"nama" => "Sandhika Galih",
		"email" => "sandhikagalih@unpas.ac.id",
		"jurusan" => "Teknik Informatika",
		"gambar" => "sandhika.jpeg"
	],
	[
		"nama" => "Doddy Ferdiansyah", 
		"nrp" => "033040001",
		"email" => "doddy@gmail.com",
		"jurusan" => "Teknik Industri",
		"gambar" => "doddy.jpg"
	]
];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Daftar Mahasiswa</title>
</head>
<body>
	<h1>Daftar Mahasiswa</h1>

	<?php foreach( $mahasiswa as $mhs ) : ?>
		<ul>
			<li>
				<img src="img/<?= $mhs["gambar"]; ?>">
			</li>
			<li>Nama : <?= $mhs["nama"]; ?></li>
			<li>NRP : <?= $mhs["nrp"]; ?></li>
			<li>Jurusan : <?= $mhs["jurusan"]; ?></li>
			<li>Email : <?= $mhs["email"]; ?></li>
		</ul>
	<?php endforeach; ?>




</body>
</html>