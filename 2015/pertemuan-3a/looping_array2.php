<?php 
// Menampilkan seluruh isi array menggunakan looping 
// ada beberapa cara untuk menampilkan seluruh isi array menggunakan looping
// cara pertama dengan menggunakan 'for', tetapi kita harus tau dulu jumlah total isi array
// cara mengetahuinya dengan menggunakan fungsi count
// cara kedua adalah dengan menggunakan fungsi looping spesial untuk array
// fungsi spesial tersebut adalah foreach


// Cara 2, mengunakan foreach

// contoh array
$teman = array("arief", "budi", "ratih", "doddy", "susan");
?>

<!doctype html>
<html>
<head>
	<title>Looping Array 2</title>
</head>
<body>

	<h1>Looping Array 2</h1>
	<h3>Daftar teman:</h3>
	<ul>
		<?php 
			// menggunakan foreach sehingga tidak harus menghitung isi arraynya lagi
			// sintaksnya adalah
			// foreach ($array as $key) {
			// 	# code...
			// }
			// kita bebas mendefinisikan nama $key nya
		    foreach($teman as $nama) {
		    	echo "<li>$nama</li>";
		    } 
		?>
	</ul>

</body>
</html>