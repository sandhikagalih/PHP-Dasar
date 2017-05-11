<?php 
// Pertemuan 1b
// Latihan 6
// membuat fungsi dengan default parameter
?>

<!doctype html>
<html>
<head>
	<title>Praktikum 1 - Latihan 6</title>
</head>
<body>

	<?php 
	    function panggil_nama($nama_depan = "John", $nama_belakang = "Doe") {

	    	echo "Halo, " . $nama_depan . " " . $nama_belakang;

	    }

	    panggil_nama();
	    echo "<br>";
	    panggil_nama("Sandhika");
	    echo "<br>";
	    panggil_nama("Sandhika", "Galih");

	?>

</body>
</html>