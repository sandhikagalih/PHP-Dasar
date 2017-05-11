<?php 
/*
	Array
	Latihan 3
	Associative Array	
*/ 	

$kota = array(
	"Aceh" 		 => "Mie Aceh",
	"Padang"	 => "Rendang",
	"Jakarta"	 => "Kerak Telor",
	"Bandung"	 => "Batagor",
	"Garut"		 => "Dodol",
	"Yogyakarta" => "Gudeg",
	"Semarang"	 => "Lumpia",
	"Palembang"	 => "Pempek"
);
asort($kota);
?>

<!doctype html>
<html>
<head>
	<title>Latihan 3</title>
</head>
<body>
	<h3>Daftar Makanan Khas Indonesia:</h3>
	<ul>
	<?php foreach ($kota as $hasil => $makanan) : ?>

	    <li><?=$hasil . " : " . $makanan; ?></li>

	<?php endforeach; ?>
	</ul>


</body>
</html>