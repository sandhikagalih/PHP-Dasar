<!DOCTYPE html>
<html>
<head>
	<title>Daftar Teman</title>
</head>
<body>

	<form action="get.php">

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

	<ul>
		<li><a href="get.php?nama=Sandhika">Sandhika</a></li>
		<li><a href="get.php?nama=Erik">Erik</a></li>
		<li><a href="get.php?nama=Robin">Robin</a></li>
	</ul>
	
</body>
</html>