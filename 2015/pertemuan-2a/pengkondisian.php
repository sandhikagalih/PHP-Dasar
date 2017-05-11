<?php
/* 	
	PENGKONDISIAN
	hapus komentar untuk mengetahui cara kerja dari tiap-tiap statement pengkondisian
*/

$bulan = 'Juni';

// Menggunakan statement 'if'
 
if($bulan == 'Januari') {
	echo 'Sekarang bulan Januari!';
} elseif ($bulan == 'Februari') {
	echo 'Sekarang bulan Februari!';
} elseif ($bulan == 'Maret') {
	echo 'Sekarang bulan Maret!';
} else {
	echo 'Sekarang bukan bulan ' . $bulan;
}

// Menggunakan statements 'switch'
 
switch($bulan) {
	case 'Januari':
		echo 'Bulan Januari!';
		break;

	case 'Februari':
		echo 'Bulan Februari!';
		break;

	case 'Maret':
		echo 'Bulan Maret!';
		break;		

	default:
		echo 'Sekarang bukan bulan ' . $bulan;
}

?>