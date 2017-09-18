<?php 
$jml_angkot = 10;
$no_angkot = 1;
$angkot_beroperasi = 6;

while( $no_angkot <= $angkot_beroperasi ) {
	echo "Angkot No. $no_angkot beroperasi dengan baik. <br>";
$no_angkot++;
}

for( $no_angkot = $angkot_beroperasi + 1; $no_angkot <= $jml_angkot; $no_angkot++ ) {
	echo "Angkot No. $no_angkot sedang tidak dapat beroperasi <br>";
}





?>