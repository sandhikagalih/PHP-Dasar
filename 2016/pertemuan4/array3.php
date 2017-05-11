<?php 
$bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni"];

// dengan for
// for($i = 0; $i < count($bulan); $i++) {
// 	echo $bulan[$i];
// 	echo "<br>";
// }

// dengan foreach
foreach( $bulan as $nilai ) {
	echo $nilai;
	echo "<br>";
}

echo "<br>";
echo "<hr>";

$array4 = [
	[2,4,6],
	[1,3,5]
];

// for( $i = 0; $i < count($array4); $i++ ) {
// 	for( $j = 0; $j < count($array4[$i]); $j++) {
// 		echo $array4[$i][$j];
// 	}
// 	echo "<br>";
// }

foreach( $array4 as $baris ) {
	foreach( $baris as $kolom) {
		echo $kolom;
	}
	echo "<br>";
}

echo "<hr>";

$mhs = [
	["Sandhika", "043040023", "sandhika@unpas.ac.id"],
	["Doddy", "033040001", "doddy@unpas.ac.id"],
	["Fajar", "023040012", "fajar@unpas.ac.id"]
];









foreach( $mhs as $mahasiswa  ) {
	echo "<ul>";
		foreach( $mahasiswa as $value ) {
			echo "<li>$value</li>";
		}
	echo "</ul>";
}






?>