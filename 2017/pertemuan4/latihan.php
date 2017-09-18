<?php 

function tampilkan_bintang($baris) {
	for( $i = 0; $i < $baris; $i++ ) {
		for( $j = 0; $j <= $i; $j++ ) {
			echo "*";
		}
		echo "<br>";
	}
}


tampilkan_bintang(10);


?>