<?php 
session_start();

if( isset($_SESSION["username"]) ) {
	header("Location: admin.php");
	die;
}

if( isset($_POST["submit"]) ) {

	if( $_POST["username"] == "admin" && $_POST["password"] == "123" ) {
		$_SESSION["username"] = $_POST["username"];
		header("Location: admin.php");
		die;
	} else {
		$error = true;
	}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Halaman Login</title>
</head>
<body>
	<h1>Halaman Login</h1>
	<?php if( isset($error) ) : ?>
		<p style="color: red; font-style: italic;">username / password salah</p>
	<?php endif; ?>
	<form action="" method="post">
		<label for="username">Masukkan Username :</label>
		<input type="text" name="username" id="username" autofocus autocomplete="off">
		  <br>
		<label for="password">Masukkan Password : </label>
		<input type="password" name="password" id="password">
		  <br>
		<button type="submit" name="submit">Login</button>
	</form>

</body>
</html>