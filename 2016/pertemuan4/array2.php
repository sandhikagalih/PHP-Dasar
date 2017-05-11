<?php 
// Array Multidimensi
$array3 = ["tulisan", 30, false, ["sandhika", 100]];
echo $array3[3][1];
echo "<br>";
$matriks = [
	[5,10,12],
	[30,2,4],
	[25,1,9]
];
// cetak 1
echo $matriks[2][1];
echo "<br>";
// cetak 9
echo $matriks[2][2];
echo "<br>";
echo "<hr>";

// Data Mahasiswa
$mhs = [
	["Sandhika", "043040023", "sandhika@unpas.ac.id"],
	["Doddy", "033040001", "doddy@unpas.ac.id"],
	["Fajar", "023040012", "fajar@unpas.ac.id"]
];
echo $mhs[2][2];




?>