<?php 
if( !isset($_GET["nama"]) ) {
	$_GET["nama"] = "Admin";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coba GET</title>
</head>
<body>
<h1>Selamat datang, <?php echo $_GET["nama"]; ?></h1>
	
</body>
</html>