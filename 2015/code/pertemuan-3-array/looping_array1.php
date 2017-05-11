<?php 
// Menampilkan seluruh isi array menggunakan looping 
// ada beberapa cara untuk menampilkan seluruh isi array menggunakan looping
// cara pertama dengan menggunakan 'for', tetapi kita harus tau dulu jumlah total isi array
// cara mengetahuinya dengan menggunakan fungsi count
// cara kedua adalah dengan menggunakan fungsi looping spesial untuk array
// fungsi spesial tersebut adalah foreach


// Cara 1, mengunakan for dan count()

// contoh array
$teman = array("arief", "budi", "ratih", "doddy", "susan");
?>

<!doctype html>
<html>
<head>
	<title>Looping Array 1</title>
</head>
<body>

	<h1>Looping Array 1</h1>
	<h3>Daftar teman:</h3>
	<ul>
		<?php 
			// gunakan count($array) untuk menentukan jumlah total isi array
			// sehingga looping nya berhenti ketika isi arraynya sudah tampil semua
		    for($i=0; $i<count($teman); $i++)
		    {
		    	echo "<li>$teman[$i]</li>";
		    } 
		?>
	</ul>

</body>
</html>