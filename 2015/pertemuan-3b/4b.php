<?php 
/*
	Array
	Latihan 4
	Array Multidimensi	
*/ 	

$kota = array(
	"Aceh"       => array("Mie Aceh", "15.000"),
	"Padang"     => array("Rendang", "10.000"),
	"Jakarta"    => array("Kerak Telor", "8.000"),
	"Bandung"    => array("Batagor", "16.000"),
	"Garut"      => array("Dodol", "5.000"),
	"Yogyakarta" => array("Gudeg", "12.000"),
	"Semarang"   => array("Lumpia", "10.000"),
	"Palembang"  => array("Pempek", "15.000")
);
ksort($kota);
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
		foreach ($kota as $nama => $makanan_harga) {
			echo "<tr>";
			  echo "<td>$nama</td>";
			  //looping isi array $makanan_harga
			  for($i=0; $i < count($makanan_harga); $i++) {
			  	echo "<td>$makanan_harga[$i]</td>";
			  }
			  // foreach ($makanan_harga as $mknhrg) {
			  // 	echo "<td>$mknhrg</td>";
			  // }
			echo "</tr>";
		}
	?>
	</table>

</body>
</html>





