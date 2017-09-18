<?php 
session_start();
require 'functions.php';

// cek cookie
if( isset($_COOKIE['id']) && isset($_COOKIE['hash']) ) {
	$id = $_COOKIE['id'];
	$hash = $_COOKIE['hash'];

	// cek username berdasarkan id
	$result = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $id");
	$row = mysqli_fetch_assoc($result);

	if( $hash === hash('sha256', $row['username'], false) ) {
		// set session
		$_SESSION['username'] = $row['username'];
		// masuk ke halaman index
		header('Location: index.php');
		exit;
	}


}

// cek session
if( isset($_SESSION['username']) ) {
	header("Location: index.php");
	exit;
}

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
			// jika berhasil login
			$_SESSION['username'] = $_POST['username'];

			// jika remember di ceklis
			if( isset($_POST['remember']) ) {
				// buat cookie
					setcookie('id', $row['user_id'], time() + 60 * 60 * 24);
				$hash = hash('sha256', $row['username'], false);
				setcookie('hash', $hash, time() + 60 * 60 * 24);
			}

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
				<input type="checkbox" name="remember" id="remember">
				<label for="remember">Remember Me</label>
			</li>
			<li>
				<button type="submit" name="login">Login</button>
			</li>
		</ul>
	</form>
</body>
</html>