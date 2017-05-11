<?php
	/* 
		PENGULANGAN / LOOPS
	*/

	// Sintaks Pengulangan 1
	echo "Pengulangan menggunakan for: <br>";
	for( $i = 1; $i <= 10; $i++ ) {
		echo "<li>angka ke-$i</li>";
	}

	echo "<br><br>";

	// Sintaks Pengulangan 2
	echo "Pengulangan menggunakan while: <br>";
	$i = 1;
	while($i <= 10 ) {
		echo "<li>angka ke-$i</li>";
		$i++;
	}
?>