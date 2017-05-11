<?php 
$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi ke Database GAGAL!");
mysqli_select_db($conn, "coba_mysql") or die("database salah!");
?>

<!doctype html>
<html>
<head>
	<title>Daftar Teman</title>
</head>
<body>

<h1>Daftar mahasiswa</h1>

<?php 
	$query = "SELECT * FROM mahasiswa";

	$result = mysqli_query($conn, $query);

	while ( $row = mysqli_fetch_assoc($result) ) : ?>
	
	<ul>
		<li><strong><?= $row["nama"]; ?></strong></li>
		<li><?= $row["universitas"]; ?></li>
	</ul>
	
	<?php endwhile; ?>

</body>
</html>
