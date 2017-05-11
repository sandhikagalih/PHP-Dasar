<?php 
// Pertemuan 1b
// Latihan 5
// membuat fungsi untuk mengubah style tulisan
?>

<!doctype html>
<html>
<head>
	<title>Praktikum 1 - Latihan 5</title>
	<style>
		.ubah-teks {font-weight: bold; font-size: 24px; font-family: arial; color: green;}
	</style>
</head>
<body>

	<?php 
	    function tambah_kelas($tulisan, $kelas) {
	    	$hasil = "<span class=\"$kelas\">" . $tulisan . "</span>";
	    	return $hasil;
	    }

	    $tulisan = "Hallo, Selamat Datang!";
	    $kelas = "ubah-teks";

	    echo tambah_kelas($tulisan, $kelas);


	?>

</body>
</html>