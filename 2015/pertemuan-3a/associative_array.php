<?php 
/*
Associative Array
hampir sama seperti array biasa
namun mengganti index yang tadinya adalah angka 0,1,2...
menjadi key tersendiri
sintaks:
	$array = array(
		"key1" => "value1",
		"key2" => "value2",
		"key3" => "value3", 
		...       ...
	);
*/



// contoh deklarasi associative array
$teman = array(
	"budi"  => "sibuditea@yahoo.com",
	"arief" => "my_name_is_arief@gmail.com",
	"ratih" => "violet.ratih123@mail.unpas.ac.id",
	"doddy" => "doddy2112@unpas.ac.id",
	"susan" => "uchan_uchu@hotmail.com"
);
?>

<!doctype html>
<html>
<head>
	<title>Assosiative Array</title>
</head>
<body>

	<h1>Associative Array</h1>
	<h3>Daftar Teman</h3>
	<ul>
		<?php 
			// masih menggunakan foreach
			// tapi perhatikan ada sintaks =>
			// yang berarti $array as $key => $value

		    foreach($teman as $nama => $email) : ?>
		    	
		    	<li><?=$nama; ?> : <?=$email; ?></li>

		    <?php endforeach ?>
	</ul>

</body>
</html>