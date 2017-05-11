<?php 
require 'functions.php';
$conn = konek();

sleep(1);

$id = $_GET["id"];

$kota = query($conn, "SELECT * FROM kota WHERE id_provinsi = $id"); 

?>

<?php if( empty($kota) ) : ?>

	<option value="">Belum ada data</option>

<?php else : ?>

	<?php foreach( $kota as $baris ) : ?>
		<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
	<?php endforeach; ?>

<?php endif; ?>