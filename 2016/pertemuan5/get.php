<!DOCTYPE html>
<html>
<head>
	<title>Coba GET</title>
</head>
<body>
	<form>

		<label>Masukkan Nama:</label>
			<br>
		<input type="text" name="nama">
			<br>
		<label>Masukkan Email:</label>
			<br>
		<input type="text" name="email">
			<br>
		<button type="submit">Kirim!</button>
		
	</form>

	<?php if(isset($_GET["nama"])) { ?>
		<h1>Halo, <?php echo $_GET["nama"]; ?></h1>
	<?php } else { ?>
		<p>isi dulu data lewat URL</p>
	<?php } ?>
	
</body>
</html>