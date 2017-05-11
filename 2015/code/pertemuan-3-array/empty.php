<?php 
/*  empty()
	- berguna untuk mengecek apakah sebuah variabel ada isinya atau tidak
	- berguna juga untuk mengecek apakah variabel berisi 0 atau bukan
	- mengembalikan nilai boolean
*/ 

$x = 0;
$y = 1;

echo "Jika \$x = 0 dan \$y = 1";
echo "<br>";

if (empty($x)) {
	echo "variabel \$x bernilai empty (true)";
	echo "<br>";
}

if (!empty($y)) {
	echo "variabel \$y tidak bernilai empty (false)";
}

?>