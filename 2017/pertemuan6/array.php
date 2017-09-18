<?php 
$mahasiswa = [
		["sandhika.jpeg", "Sandhika Galih", "043040023", "sandhikagalih@gmail.com"],
		["doddy.jpg", "Doddy", "033040001", "doddy@yahoo.com"],
		["anggoro.jpg", "023040020", "Anggoro Ari", "anggoro_ari@unpas.ac.id"]
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
			<li><img src="img/<?= $mhs[0] ?>"></li>
			<li><?= $mhs[1] ?></li>
			<li><?= $mhs[2] ?></li>
			<li><?= $mhs[3] ?></li>
		</ul>
	<?php endforeach; ?>






</body>
</html>