<!doctype html>
<html>
<head>
	<title>Sanitasi Input</title>
</head>
<body>

<?php 
 
 if (isset($_POST["keyword"])) {
 	echo "<h1>Keyword yang dimasukkan adalah : " . htmlspecialchars($_POST["keyword"]) . "</h1>";
 }

?>
<form action="sanitasi.php" method="post">
	<label for="keyword">Keyword</label>
	<input type="text" name="keyword" id="keyword">
		<br>
	<input type="submit" value="submit">
</form>

</body>
</html>