<?php 
require '../helpers/functions.php';

// koneksi ke database dan memilih database
$conn = koneksi($config);

$fakultas = $_POST["fakultas"];

$nama_fakultas = query_update($conn, "SELECT nama FROM fakultas WHERE id = $fakultas");
$jurusan = query($conn, "SELECT * FROM jurusan WHERE id_fakultas = $fakultas");

?>

<option value="#" disabled><?= $nama_fakultas["nama"]; ?></option>
<?php foreach($jurusan as $row) : ?>
	<option value="<?= $row["id"] ?>"><?= $row["nama"] ?></option>
<?php endforeach;  ?>