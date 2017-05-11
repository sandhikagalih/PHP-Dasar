<?php 
require 'helper/functions.php';
$conn = konek();

$daftar_film = query($conn, "SELECT film_id, title FROM film WHERE title LIKE '{$_POST["huruf"]}%'");

echo "<ol>";
foreach( $daftar_film as $film ) : ?>
	<li><a href="#" onclick="tampilDeskripsi(<?= $film["film_id"]; ?>)"><?= $film["title"]; ?></a></li>
<?php endforeach; ?>
</ol>