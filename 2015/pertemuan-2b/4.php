<?php 
// Pertemuan 1b
// Latihan 4
// menggunakan pengkondisian untuk mewarnai baris
?>

<!doctype html>
<html>
<head>
	<title>Praktikum 1 - Latihan 4</title>
	<style>
		tr th {background-color: #333; color: white;}
		.ganjil {background-color: white;}
		.genap {background-color: #999;}
	</style>
</head>
<body>

	<table border="1" cellspacing="0" cellpadding="3">
		
		<tr>
			<th>Kolom 1</th>
			<th>Kolom 2</th>
			<th>Kolom 3</th>
			<th>Kolom 4</th>
			<th>Kolom 5</th>
		</tr>

		<?php 
		    for($baris=1; $baris<=10; $baris++) {
		    	if ($baris%2 == 1) {
		    		$kelas = "ganjil";
		    	} else {
		    		$kelas = "genap";
		    	}
		    	echo "<tr class=\"$kelas\">";
		    	for($kolom=1; $kolom<=5; $kolom++) {
		    		echo "<td>Baris " . $baris . ", Kolom " . $kolom . "</td>";
		    	}
		    	echo "</tr>";
		    } 
		?>

	</table>

</body>
</html>