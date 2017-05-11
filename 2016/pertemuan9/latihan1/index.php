<?php 
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi Gagal");
// pilih database
mysqli_select_db($conn, "pw2_043040023") or die("Database Salah");

// query ke database
$result = mysqli_query($conn, "SELECT * FROM mahasiswa");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tampil Data Mahasiswa</title>
</head>
<body>

<h2>Data Mahasiswa</h2>
<?php while( $row = mysqli_fetch_assoc($result) ) { ?>
	<ul>
		<li><img src="../img/<?php echo $row["gambar"]; ?>"></li>
		<li><?php echo $row["nama"]; ?></li>
		<li><?php echo $row["email"]; ?></li>
		<li><?php echo $row["jurusan"]; ?></li>
		<li><?php echo $row["universitas"]; ?></li>
	</ul>
<?php } ?>
	
</body>
</html>