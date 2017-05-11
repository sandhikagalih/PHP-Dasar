<?php 
if( !isset($_GET["id"]) ) {
	header("location: index.php");
	exit;
}

sleep(1);

require 'helper/functions.php';
$conn = konek();

$id = $_GET["id"];
$film = query($conn, "SELECT * FROM film WHERE film_id = $id LIMIT 1");
?>

<!doctype html>
<html>
<head>
	<title>Detail Film</title>
</head>
<body>
<div class="result">
	<h3><?= $film[0]["title"]; ?></h3>
	<p><strong>Release Year</strong> : <?= $film[0]["release_year"]; ?></p>
	<h4>Description:</h4>
	<p><?= $film[0]["description"]; ?></p>
</div>
</body>
</html>