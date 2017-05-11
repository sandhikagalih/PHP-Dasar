<?php 
/*
	Array
	Latihan 2
	Menambahkan elemen pada array dan mengurutkannya	
*/ 	

$kota = array("Aceh", "Padang", "Jakarta", "Bandung", "Garut");
?>

<!doctype html>
<html>
<head>
	<title>Latihan 2</title>
</head>
<body>
	<h3>Daftar Kota Asal:</h3>
	<ul>
	<?php foreach ($kota as $hasil) : ?>

	    <li><?=$hasil; ?></li>

	<?php endforeach; ?>
	</ul>

	<h3>Daftar Kota Baru:</h3>
	<?php 
	    array_push($kota, "Yogyakarta", "Semarang", "Palembang");
	    sort($kota); 
	?>
	<ul>
	<?php foreach ($kota as $kota) : ?>
		<li><?=$kota; ?></li>
	<?php endforeach; ?>
	</ul>

</body>
</html>