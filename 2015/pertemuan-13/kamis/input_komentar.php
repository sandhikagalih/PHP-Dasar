<?php 
require 'functions.php';
$conn = konek();

if( simpan($conn, $_POST) > 0 ) {

	$komentar = query($conn, "SELECT * FROM komentar ORDER BY waktu DESC");
?>

<ul>
	<?php foreach( $komentar as $baris ) : ?>
		<li>(<?= $baris["waktu"]; ?>) <?= $baris["nama"]; ?> : <?= $baris["pesan"]; ?></li>
	<?php endforeach; ?>
</ul>

<?php
} else {
	echo "komentar gagal ditambahkan";
}
?>