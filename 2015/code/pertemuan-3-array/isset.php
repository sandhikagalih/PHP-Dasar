<?php 
/*  isset()
	- berguna untuk mengecek apakah sebuah variabel sudah pernah dideklarasikan,
	  atau belum
	- berguna juga untuk mengecek apakah variabel berisi NULL atau tidak
	- mengembalikan nilai boolean
*/

// $variabel_ku belum di deklarasikan
if (!isset($variabel_ku)) {
	echo "\$variabel_ku BELUM di-set";
	echo "<br><hr>";
}

// deklarasi $variabel_ku
$variabel_ku = "Sandhika Galih";

if (!isset($variabel_ku)) {
	echo "\$variabel_ku BELUM di-set";
	echo "<br><hr>";
} else {
	echo "Sekarang \$variabel_ku SUDAH di-set, dan isinya adalah $variabel_ku";
	echo "<br><hr>";
}

// isi $variabel_ku dengan NULL
$variabel_ku = NULL;

if (!isset($variabel_ku)) {
	echo "Sekarang \$variabel_ku berisi NULL";
}

?>