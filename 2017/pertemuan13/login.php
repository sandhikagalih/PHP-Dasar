<?php 
session_start();
require 'functions.php';

// jika tombol login ditekan
if( isset($_POST['login']) ) {

	// cek login
	// cek usernamenya dulu
	global $conn;
	$username = $_POST['username'];
	$password = $_POST['password'];
	$cek_username = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

	if( mysqli_num_rows($cek_username) === 1 ) {
		$row = mysqli_fetch_assoc($cek_username);
		// cek password
		if( password_verify($password, $row['password']) ) {
			$_SESSION['username'] = $_POST['username'];
			header('Location: index.php');
			exit;
		}
	}
	
	$error = true;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Halaman Login</title>
	<style>
		ul li { list-style-type: none; }
	</style>
</head>
<body>
	<h3>Login Administrator</h3>
	<?php if( isset($error) ) : ?>
		<p style="color: red; font-style: italic;">username / password salah!</p>
	<?php endif; ?>
	<form action="" method="post">
		<ul>
			<li>
				<label for="username">Username :</label>
				<input type="text" name="username" id="username" autofocus>
			</li>
			<li>
				<label for="password">Password :</label>
				<input type="password" name="password" id="password">
			</li>
			<li>
				<button type="submit" name="login">Login</button>
			</li>
		</ul>
	</form>
</body>
</html>