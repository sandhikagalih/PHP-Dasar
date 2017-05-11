<?php 
// Pertemuan 1b
// Latihan 3
// menggunakan pengulangan untuk men-generate tabel
?>

<!doctype html>
<html>
<head>
	<title>Modul 2 - Latihan 3</title>
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
		    	echo "<tr>";
		    	for($kolom=1; $kolom<=5; $kolom++) {
		    		echo "<td>Baris " . $baris . ", Kolom " . $kolom . "</td>";
		    	}
		    	echo "</tr>";
		    } 
		?>

	</table>

</body>
</html>