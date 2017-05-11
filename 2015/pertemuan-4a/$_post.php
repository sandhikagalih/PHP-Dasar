<!doctype html>
<html>
<head>
	<title>$_POST</title>
	<style>
	ul {margin: 0; padding: 0;}
	</style>
</head>
<body>

<form action="cek.php" method="post">
	<label for="nama">Nama</label>
	<input type="text" name="nama">
	 <br>	
	<label for="gender">Jenis Kelamin:</label>
	 <br>
	<input type="radio" name="gender" value="Bapak" checked> Pria 
	<input type="radio" name="gender" value="Ibu"> Wanita
	 <br>
	<input type="submit" value="Submit">
</form>

</body>
</html>