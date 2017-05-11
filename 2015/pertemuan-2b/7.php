<?php 
/* Pertemuan 1b
   Latihan 7
   bermain dengan fungsi date() dan mktime()
   mengetahui tanggal hari ini dan tanggal 100 hari yang akan datang
   mengetahui hari dari tanggal lahir seseorang
*/
?>

<!doctype html>
<html>
<head>
	<title>Praktikum 1 - Latihan 7</title>
</head>
<body>

	<?php 

	    echo "Sekarang adalah tanggal : " . date("j F Y") . "(waktu pembuatan soal)<br>";
	    echo "dan 100 hari lagi adalah tanggal : " . date("j F Y", (time()+(60*60*24*100)));
	    echo "<br>";
	    echo "Saya lahir tanggal: 25 Agustus 1985 <br>";
	    echo "Saat itu adalah hari : " . date("l", mktime(0, 0, 0, 8, 25, 1985)) . " (tidak apa-apa menggunakan bahasa inggris karena hasil dari fungsi php)";
	    
	?>

</body>
</html>