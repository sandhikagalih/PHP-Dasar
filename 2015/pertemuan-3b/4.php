<?php 
/*
	Array
	Latihan 4
	Array Multidimensi	
*/ 	

$kota = array(
	array("Aceh", "Mie Aceh", "15.000"),
	array("Padang", "Rendang", "10.000"),
	array("Jakarta", "Kerak Telor", "8.000"),
	array("Bandung", "Batagor", "16.000"),
	array("Garut", "Dodol", "5.000"),
	array("Yogyakarta", "Gudeg", "12.000"),
	array("Semarang", "Lumpia", "10.000"),
	array("Palembang", "Pempek", "15.000")
);
?>

<!doctype html>
<html>
<head>
	<title>Latihan 4</title>
</head>
<body>
	<h3>Daftar Makanan Khas Indonesia:</h3>

	<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<th>Kota</th>
		<th>Makanan</th>
		<th>Harga</th>
	</tr>
	<?php 
	 for($baris = 0; $baris < count($kota); $baris++) {
	 	echo "<tr>";
	 	foreach ($kota[$baris] as $hasil) {
	 		echo "<td>" . $hasil . "</td>";
	 	}
	 	echo "</tr>";
	 }
	?>

		
	</table>

</body>
</html>