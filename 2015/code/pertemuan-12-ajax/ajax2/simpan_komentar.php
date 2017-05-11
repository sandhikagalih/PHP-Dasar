<?php 
require 'functions.php';
$conn = konek();


if( simpan($conn, $_POST) > 0) {
	$komentar = query($conn, "SELECT * FROM komentar ORDER BY id DESC");

	foreach( $komentar as $baris ) {
		echo "<li>
				<span class='tanggal'>({$baris["waktu"]})</span>
				<span class='nama'>{$baris["nama"]} : </span>
				<span class='komentar'>{$baris["pesan"]}</span>
			  </li>";
	}

} else {
	echo "terjadi kesalahan";
}

?>