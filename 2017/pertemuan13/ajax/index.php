<?php 
require '../functions.php';
$mahasiswa = query("SELECT * FROM mahasiswa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>COBA AJAX</title>
</head>
<body>
	
	<select id="idMhs">
		<?php foreach( $mahasiswa as $mhs ) : ?>
			<option value="<?= $mhs['id']; ?>"><?= $mhs['id']; ?></option>
		<?php endforeach; ?>
	</select>

	<div id="wadah"></div>

<script src="script.js"></script>
</body>
</html>