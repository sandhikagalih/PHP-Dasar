<?php 
require 'functions.php';

$conn = konek();

?>

<!doctype html>
<html>
<head>
	<title>Data Karyawan</title>
</head>
<body>

<h3>Daftar Karyawan</h3>
<a href="tambah.php">Tambah Data Karyawan</a>

	<?php 

	 	$result = query($conn, "SELECT * FROM karyawan");

	 	foreach( $result as $row ) : ?>
 		<ul>
 			<li><?= $row["nama"]; ?></li>
 			<li><a href="ubah.php?id=<?= $row["id"]; ?>">Ubah</a> | <a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('yakin?');">Hapus</a></li>
		</ul>
		<?php endforeach; ?>


</body>
</html>




