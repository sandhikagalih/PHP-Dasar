<?php 
session_start();
	// cek apakah ada cookie
	if( isset($_COOKIE["username"]) && isset($_COOKIE["password"]) ) {
		// jika ada, cek kebenarannya
		if( $_COOKIE["username"] == "admin" && $_COOKIE["password"] == "123" ) {
			// set session, dengan cookie
			$_SESSION["username"] = $_COOKIE["username"];

			// arahkan user ke halaman index
			header("Location: index.php");
			exit;
		}
	}

	// cek apakah sudah login atau belum
	if( isset($_SESSION["username"]) ) {
		header("Location: index.php");
		exit;
	}

	// inisialisasi error
	$error = false;

	// apakah tombol submit sudah ditekan
	if( isset($_POST["submit"]) ) {
		// jika sudah, cek data user
		if( $_POST["username"] == "admin" && $_POST["password"] == "123") {
			// cek apakah remember di ceklis
			if( isset($_POST["remember"]) ) {
				// set cookie untuk username dan password
				setcookie("username", $_POST["username"], time() + 60 * 60 * 24 * 7);
				setcookie("password", $_POST["password"], time() + 60 * 60 * 24 * 7);
			}

			// buat session username
			$_SESSION["username"] = $_POST["username"];

			// jika login benar, arahkan user ke halaman index.php
			header("Location: index.php");
			exit;
		} else {
			// jika username / password salah
			$error = true;
		}

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

<h1>Form Login</h1>

<?php if( $error ) { ?>
  <p style="color: red; font-style:italic">username atau password salah</p>
<?php } ?>

<form action="" method="post">
	
	<label for="username">Username :</label> <br>
	<input type="text" name="username" id="username"> <br>

	<label for="password">Password :</label> <br>
	<input type="password" name="password" id="password"> <br>

	<label for="remember">Ingat Saya</label>
	<input type="checkbox" name="remember" id="remember"> <br>

	<button type="submit" name="submit">Login</button>

</form>











</body>
</html>