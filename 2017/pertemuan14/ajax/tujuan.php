<?php 
require '../functions.php';
$id = $_GET['id'];

$mahasiswa = query("SELECT * from mahasiswa WHERE id = $id");
?>
<?php foreach( $mahasiswa as $mhs ) : ?>
	<ul>
		<li><img src="../img/<?= $mhs['gambar']; ?>"></li>
		<li><?= $mhs['nrp']; ?></li>
	</ul>
<?php endforeach; ?>