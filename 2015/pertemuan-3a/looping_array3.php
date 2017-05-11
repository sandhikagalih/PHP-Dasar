<?php 
// Cara 3, masih mengunakan foreach
// tetapi sintaks ini lebih baik, karena memisahkan html dengan php
// dengan cara tidak melakukan echo untuk elemen html
// cth echo "<li>$nama</li>"; <- sintaks ini kurang baik karena php & html digabung


// contoh array
$teman = array("arief", "budi", "ratih", "doddy", "susan");
?>

<!doctype html>
<html>
<head>
	<title>Looping Array 3</title>
</head>
<body>

	<h1>Looping Array 3</h1>
	<h3>Daftar teman:</h3>
	<ul>
		<?php 
			// masih menggunakan foreach
			// tapi perhatikan sintaks { nya digantikan oleh :
			// dan } nya digantikan oleh endforeach
			// foreach ($array as $key) : 
			// 	# code html
			// endforeach

		    foreach($teman as $nama) : ?>
		    
		    	<li><?=$nama; ?></li>
		    	
		    <?php endforeach ?>
	</ul>

</body>
</html>