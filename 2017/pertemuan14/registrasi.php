<?php 
require 'functions.php';
if( isset($_POST["register"]) ) {
	if( register($_POST) > 0 ) {
		echo "<script>
				alert('user baru berhasil ditambahkan, silahkan login!');
				document.location.href = 'login.php';
			  </script>";
	} else {
		echo "<script>
				alert('gagal menambahkan user baru!');
				document.location.href = 'login.php';
			  </script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registrasi User</title>
</head>
<body>
	<h3>Registrasi User</h3>
	<form action="" method="post">
		<ul>
			<li>
				<label for="username">Username :</label>
				<input type="text" name="username" id="username" required>
			</li>
			<li>
				<label for="password">Password :</label>
				<input type="password" name="password" id="password" required>
			</li>
			<li>
				<label for="email">Email :</label>
				<input type="text" name="email" id="email" required>
			</li>
			<li>
				<button type="submit" name="register">Register</button>
			</li>
		</ul>
	</form>





</body>
</html>