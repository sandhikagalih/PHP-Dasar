<?php 
	if( !isset($_GET["nama"]) || 
		!isset($_GET["gambar"]) ||
		!isset($_GET["email"]) ||
		!isset($_GET["jurusan"]) ||
		!isset($_GET["universitas"]) ) {

		header("Location: latihan3.php");
		exit;
	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Detail</title>
</head>
<body>

<ul>
	<li><img src="images/<?php echo $_GET["gambar"]; ?>"></li>
	<li><?php echo $_GET["nama"]; ?></li>
	<li><?php echo $_GET["email"]; ?></li>
	<li><?php echo $_GET["jurusan"]; ?></li>
	<li><?php echo $_GET["universitas"]; ?></li>
	<li><a href="latihan3.php">Kembali</a></li>
</ul>

</body>
</html>