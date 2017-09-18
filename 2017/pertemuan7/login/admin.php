<?php 
session_start();
if( !isset($_SESSION["username"]) ) {
	header("Location: login.php");
	die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Halaman Admin</title>
</head>
<body>
	<a href="logout.php">logout</a>
	<h1>Halaman Administrator</h1>
</body>
</html>