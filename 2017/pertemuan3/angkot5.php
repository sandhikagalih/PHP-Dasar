<?php 
$jml_angkot = 10;
$no_angkot = 1;
$angkot_beroperasi = 6;

for( $no_angkot = 1; $no_angkot <= $jml_angkot; $no_angkot++ ) {

	if( $no_angkot <= $angkot_beroperasi && $no_angkot !== 5 ) {
		echo "Angkot No. $no_angkot beroperasi dengan baik. <br>";
	} else if( $no_angkot === 8 || $no_angkot === 10 || $no_angkot === 5) {
		echo "Angkot No. $no_angkot sedang lembur <br>";
	} else {
		echo "Angkot No. $no_angkot sedang tidak dapat beroperasi <br>";
	}

}





?>