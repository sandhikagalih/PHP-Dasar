<?php 
session_start();

if( !isset($_SESSION["username"]) ) {
	header("Location: login.php");
	exit;
}














$mahasiswa = [
	[
		"nama" => "Sandhika Galih",
		"email" => "sandhikagalih@unpas.ac.id",
		"jurusan" => "Teknik Informatika",
		"universitas" => "Universitas Pasundan",
		"gambar" => "sandhika.jpg"
	],
	[
		"nama" => "Erik",
		"email" => "eikic1@unpas.ac.id",
		"jurusan" => "Teknik Informatika",
		"universitas" => "Universitas Pasundan",
		"gambar" => "erik.jpg"
	],
	[
		"nama" => "Anggoro Ari",
		"email" => "anggoro.ari@gmail.com",
		"jurusan" => "Teknik Mesin",
		"universitas" => "Universitas Pasundan",
		"gambar" => "anggoro.jpg"
	],
	[
		"nama" => "Nofariza Handayani",
		"email" => "nofa@gmail.com",
		"jurusan" => "Ilmu Ekonomi",
		"universitas" => "Universitas Padjadjaran",
		"gambar" => "nofariza.jpg"
	],
	[
		"nama" => "Shabrina Gea",
		"email" => "gea@yahoo.com",
		"jurusan" => "Psikologi",
		"universitas" => "Universitas Islam Bandung",
		"gambar" => "shabrinagea.jpg"
	]

];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Daftar Mahasiswa</title>
</head>
<body>

<a href="logout.php">Logout</a>


<h1>Daftar Mahasiswa</h1>

<?php foreach( $mahasiswa as $mhs ) { ?>
	<ul>
		<li><img src="images/<?php echo $mhs["gambar"]; ?>"></li>
		<li><a href="latihan4.php?nama=<?php echo $mhs["nama"]; ?>&email=<?php echo $mhs["email"]; ?>&gambar=<?php echo $mhs["gambar"]; ?>&jurusan=<?php echo $mhs["jurusan"]; ?>&universitas=<?php echo $mhs["universitas"]; ?>"><?php echo $mhs["nama"]; ?></a></li>
		<li><?php echo $mhs["universitas"] ?></li>
	</ul>
<?php } ?>

</body>
</html>
















