<?php 
require 'functions.php';
$conn = konek();

$id = $_GET["id"];

$kota = query($conn, "SELECT * FROM kota WHERE id_provinsi = $id"); 

?>

<?php foreach( $kota as $baris ) : ?>
	<option value="<?= $baris["id"]; ?>"><?= $baris["nama"]; ?></option>
<?php endforeach; ?>